<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>   
   
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css"/>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
  
  <script type="text/javascript">
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 430,
            width: 700,
            modal: true,
            autoOpen:false
        });
		
		$( "#dialog-modal-password" ).dialog({
            height: 340,
            width: 550,
            modal: true,
            autoOpen:false
        });
				
		get_skpd();

        });    
     
     $(function(){ 
        
     /*$('#nip').combogrid({  
       panelWidth:500,  
       idField:'nip',  
       textField:'nip',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_nip',  
       columns:[[  
           {field:'nip',title:'NIP',width:150},  
           {field:'nama',title:'Nama',width:320}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#no_simpan").attr("value",rowData.nip);   
           $("#nama").attr("value",rowData.nama);  
           $("#jabat").attr("value",rowData.jabatan);               
           $("#pang").attr("value",rowData.pangkat);
           $("#kd").attr("value",rowData.kode);           
       }  
     });  */ 
     
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/sirup/master/ambil_niprup',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'username',
    		title:'User',
    		width:10,
            align:"center"},
            {field:'nama',
    		title:'Nama',
    		width:15},
            {field:'nm_skpd',
    		title:'SKPD',
    		width:20},
            {field:'rool',
    		title:'Peran',
    		width:3,
            align:"center"}            
        ]],
        onSelect:function(rowIndex,rowData){          
          nip = rowData.nip;
          nrp = rowData.nrp;
          nik = rowData.nik;
          nm = rowData.nama;
          jab = rowData.jabatan;
          pang = rowData.pangkat;
          gol = rowData.golongan;
          stt = rowData.stt_user;
          alamat = rowData.alamat;
          telp = rowData.telp;
          email = rowData.email;
          no_sk = rowData.no_sk;
          dns = rowData.kd_skpd;
          kd = rowData.kode;
		  usernm = rowData.username;
		  roolrup = rowData.roolrup;
		  id_user = rowData.id_user;
          get(nip,nrp,nik,nm,jab,pang,gol,stt,dns,kd,alamat,telp,email,no_sk,usernm,roolrup,id_user);         
          lcidx = rowIndex;                                  
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data'; 
           edit_data();   
        }
        
        });
       
    });        

	function get_skpd() {
			
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/sirup/master/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
				$("#dinas").attr("value",data.kd_skpd);
				$("#nm_u").attr("value",data.nm_skpd);
                //$("#usern").attr("value",data.usernm);
				 }	
        	});  
        }
    
    function get(nip,nrp,nik,nm,jab,pang,gol,stt,dns,kd,alamat,telp,email,no_sk,usernm,roolrup,id_user){
		
		$('#bid_log_user').attr("value",id_user);
		$('#buser_log_user').attr("value",usernm);
		$('#brool_log_user').attr("value",roolrup);
		
		$("#usern").attr("value",usernm);
        $("#nip").attr("value",nip);            
        $("#nrp").attr("value",nrp);
        $("#nik").attr("value",nik);        
        $("#no_simpan").attr("value",nip);
        $("#nama").attr("value",nm); 
        $("#dinas").attr("value",dns); 
        $("#jabat").attr("value",jab);
        $("#pang").attr("value",pang);        
        $("#goll").attr("value",gol);
        $("#kd").attr("value",kd);
        $("#stt_user").attr("value",stt);
        $("#alamat").attr("value",alamat); 
        $("#telp").attr("value",telp);         
        $("#email").attr("value",email);         
        $("#nosk").attr("value",no_sk); 
                              
    }
       
    function kosong(){
		$("#nip").attr("value","");
        $("#nrp").attr("value","");
        $("#nik").attr("value","");        
        $("#no_simpan").attr("value","");
        $("#nama").attr("value","");
        $("#usern").attr("value","");
        $("#jabat").attr("value","");
        $("#pang").attr("value","");        
        $("#gol").attr("value","");
        $("#kd").attr("value","");
        $("#stt_user").attr("value","");
        $("#alamat").attr("value",""); 
        $("#telp").attr("value","");         
        $("#email").attr("value","");         
        $("#nosk").attr("value","");
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/sirup/master/ambil_niprup',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    
  function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
         month = '0'+month;
    }
    if(day.toString().length == 1) {
         day = '0'+day;
    }   
    if(hour.toString().length == 1) {
         hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
         minute = '0'+minute;
    }
    if(second.toString().length == 1) {
         second = '0'+second;
    }   
    var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;   
     return dateTime;
    }
    
       function simpan_ttd(){
        
        var cnip = document.getElementById('nip').value;
        var no_simpan = document.getElementById('no_simpan').value;
        var cnama = document.getElementById('nama').value;
        var cdinas =  document.getElementById('dinas').value;        
        var cuser = document.getElementById('usern').value;        
        var cjabat = document.getElementById('jabat').value;
        var cpang = document.getElementById('pang').value;
        var cgol = document.getElementById('goll').value;                
        var ckode = "PPKOM";//document.getElementById('kd').value;
        var cstt_user = "1";
        var cnrp = document.getElementById('nrp').value;
        var cnik = document.getElementById('nik').value;
        var calamat = document.getElementById('alamat').value;        
        var ctelp = document.getElementById('telp').value;        
        var cemail = document.getElementById('email').value;              
        var cnosk = document.getElementById('nosk').value;        
        
        if(ckode==""){
            ckode = "PPKOM";
        }
        
        if (cnip==''){
            alert('NIP  Tidak Boleh Kosong');
			
            exit();
        } 
        if (cnama==''){
            alert('Nama  Tidak Boleh Kosong');
            exit();
        }
        if (cgol==''){
            alert('cgol  Tidak Boleh Kosong');
            exit();
        }
	    if (ctelp==''){
            alert('Telepon  Tidak Boleh Kosong');
            exit();
        }
        if (cemail==''){
            alert('Email  Tidak Boleh Kosong');
            exit();
        }
        if (cnosk==''){
            alert('NO SK  Tidak Boleh Kosong');
            exit();
        }
        if (calamat==''){
            alert('Alamat Tidak Boleh Kosong');
            exit();
        }
        
        var waktu_buat = getDateTime();
        var waktu_edit = getDateTime();  
        
       	if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cnip,jabat:ckode,tabel:'ms_ttd',field:'nip',field2:'kode'}),
                    url: '<?php echo base_url(); ?>/index.php/sirup/master/cek_simpan_ttd',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						//document.getElementById("nip").focus();
						exit();
						} 
						if(status_cek==0){
						
            lcinsert = "(nip,nama,jabatan,pangkat,golongan,status_pengguna,nrp,nik,kd_skpd,kode,alamat,telp,email,no_sk,create_time,last_update,username,jns)";
            lcvalues = "('"+cnip+"','"+cnama+"','"+cjabat+"','"+cpang+"','"+cgol+"','"+cstt_user+"','"+cnrp+"','"+cnik+"','"+cdinas+"','"+ckode+"','"+calamat+"','"+ctelp+"','"+cemail+"','"+cnosk+"','"+waktu_buat+"','"+waktu_edit+"','"+cuser+"','rup')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/sirup/master/simpan_master',
                    data: ({tabel:'ms_ttd',kolom:lcinsert,nilai:lcvalues,cid:'nip',lcid:cnip}),
                    dataType:"json",
					success:function(data){                        
                         if (data == 2){              
                            alert('Data Berhasil Tersimpan...!!!');
							lcstatus = 'edit;'
							$("#no_simpan").attr("value",cnip);
                            
                        } else{ 
                            alert('Nip Telah Dipakai. Coba tambahkan spasi!. Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
            });   
           
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
                    data: ({no:cnip,jabat:ckode,tabel:'ms_ttd',field:'nip',field2:'kode'}),
                    url: '<?php echo base_url(); ?>/index.php/sirup/master/cek_simpan_ttd',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cnip!=no_simpan){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cnip==no_simpan){
						//alert("Nomor Bisa dipakai");
						
		//-----
            lcquery = "UPDATE ms_ttd SET golongan='"+cgol+"', status_pengguna='"+cstt_user+"', nrp='"+cnrp+"', nik='"+cnik+"', alamat='"+calamat+"', telp='"+ctelp+"', email='"+cemail+"', no_sk='"+cnosk+"', create_time='"+waktu_buat+"', last_update='"+waktu_edit+"', username='"+cuser+"', nama='"+cnama+"',jabatan='"+cjabat+"',pangkat='"+cpang+"',kd_skpd='"+cdinas+"',jns='rup' ,nip='"+cnip+"' where nip='"+no_simpan+"' AND kd_skpd='"+cdinas+"' AND kode='"+ckode+"' ";
            
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sirup/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json",
				success:function(data){                        
                         if (data == 2){              
                            alert('Data Berhasil Tersimpan...!!!');
							lcstatus = 'edit;'
							$("#no_simpan").attr("value",cnip);
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
            });
            });
        }
        }
		
		});
		});
        }   
        //alert('Refresh Data...!!!');
		 swal("Berhasil","Refresh Data...");

        keluar();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nip").disabled=true;
		
        }    
    
     function tambah(){
        lcstatus = 'tambah';
        
        judul = 'Input Data';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nip").disabled=false;
        document.getElementById("nip").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 
     }    
     
	 function edit_password(){
		
		var rows = $('#dg').datagrid('getSelections'); 		
		if(rows==''){
			alert('Pilih List Pengguna');
			exit();
		}  
		  
        lcstatus = 'edit';
        judul = 'Edit Data Password';
        $("#dialog-modal-password").dialog({ title: judul });
        $("#dialog-modal-password").dialog('open');
        document.getElementById("buser_log_user").disabled=true;
		document.getElementById("bid_log_user").disabled=true;
		document.getElementById("brool_log_user").disabled=true;
        } 	
	
	 function keluar_password(){
        $("#dialog-modal-password").dialog('close');
        //$('#dg').edatagrid('reload'); 
		cari();
     }
	 
	 function simpan_password(){
        var cpass = document.getElementById("bpass_log_user").value;
		var cid = document.getElementById("bid_log_user").value;
		//alert(cpass); alert(cid);
        var urll = '<?php echo base_url(); ?>index.php/sirup/master/update_password_ppkom';
        $(document).ready(function(){
         $.post(urll,({cid:cid,cpass:cpass}),function(data){
            status = data;            
         });
        }); 
        keluar_password();
		//swal("Berhasil","Refresh Data...");	
		alert("Berhasil");
    } 
	 
     function hapus(){
        var ckode = "PPKOM";
        var cnip = $('#nip').combogrid('getValue');
        var cbidang = '2';
        var urll = '<?php echo base_url(); ?>index.php/sirup/master/hapus_master_ttdppkom';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_ttd',cnid:cnip,cid:'nip',kode:ckode,cbidang:cbidang}),function(data){
            status = data;
            if (status==1){
                $('#dg').datagrid('deleteRow',lcidx);   
					alert('Data Berhasil Dihapus..!!');
					exit();
				} else {
					alert('Gagal Hapus..!!');
					exit();
				}
         });
        }); 
        keluar();   
    } 
    
       
    function addCommas(nStr)
    {
    	nStr += '';
    	x = nStr.split(',');
        x1 = x[0];
    	x2 = x.length > 1 ? ',' + x[1] : '';
    	var rgx = /(\d+)(\d{3})/;
    	while (rgx.test(x1)) {
    		x1 = x1.replace(rgx, '$1' + '.' + '$2');
    	}
    	return x1 + x2;
    }
    
     function delCommas(nStr)
    {
    	nStr += ' ';
    	x2 = nStr.length;
        var x=nStr;
        var i=0;
    	while (i<x2) {
    		x = x.replace(',','');
            i++;
    	}
    	return x;
    }
  
    
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>KELOLA PENGGUNA<br/>RENCANA UMUM PENGGADAAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:edit_password()">Edit Password</a></td>              
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA PENGGUNA" style="width:900px;height:365px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
           <tr>
                <td width="15%">NIP</td>
                <td width="1%">:</td>
                <td><input type="text" id="nip" style="width:200px;"/> 
                    <input type="hidden" id="no_simpan" style="width:100px;"/>
                    <input type="hidden" id="kd" style="width:100px;"/>
                    <input type="hidden" id="stt_user" value="1" style="width:100px;"/>
                    <input type="hidden" id="nrp" style="width:100px;"/>
                    <input type="hidden" id="nik" style="width:100px;"/>  
                    <a><font color="red"></font></a>  
                </td>  
            </tr>            
            <tr>
                <td width="15%">NAMA </td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="15%">Jabatan </td>
                <td width="1%">:</td>
                <td><input type="text" id="jabat" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="15%">Pangkat </td>
                <td width="1%">:</td>
                <td><input type="text" id="pang" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="15%">Golongan </td>
                <td width="1%">:</td>
                <td><input type="text" id="goll" style="width:360px;"/></td>  
            </tr>            
            <tr>
                <td width="15%">SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="dinas" style="width:100px;"/>
                    <input type="hidden" id="usern" style="width:100px;"/></td>  
            </tr> 
            <tr>
                <td width="15%"></td>
                <td width="1%"></td>
                <td><input type="text" id="nm_u" style="width:400px;"/></td>  
            </tr>
            <tr>
                <td width="15%">Alamat</td>
                <td width="1%">:</td>
                <td><input type="text" id="alamat" style="width:400px;"/></td>  
            </tr>             
            <tr>
                <td width="15%">No Telp.</td>
                <td width="1%">:</td>
                <td><input type="text" id="telp" style="width:360px;"/></td>  
            </tr>            
            <tr>
                <td width="15%">Email</td>
                <td width="1%">:</td>
                <td><input type="text" id="email" style="width:360px;"/></td>  
            </tr>            
            <tr>
                <td width="15%">No. SK</td>
                <td width="1%">:</td>
                <td><input type="text" id="nosk" style="width:360px;"/></td>  
            </tr>            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_ttd();">Edit</a>
		        <!--<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>-->
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

<div id="dialog-modal-password" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
		   <tr>
                <td width="100%" colspan="3" style="border-bottom:1px solid black;"><b>DATA Login</b></td>
            </tr>
			<tr>
                <td width="15%">IDUSER</td>
                <td width="1%">:</td>
                <td><input type="text" id="bid_log_user" style="width:100px;"/>
                </td>  
            </tr>
			<tr>
                <td width="15%">Username</td>
                <td width="1%">:</td>
                <td><input type="text" id="buser_log_user" style="width:200px;"/>
                </td>  
            </tr>
			<tr>
                <td width="15%">Peran</td>
                <td width="1%">:</td>
                <td><select id="brool_log_user">
						<option value="8">PA</option>
						<option value="6">PPKOM</option>
					</select>
                </td>  
            </tr>
			<tr>
                <td width="15%">Ganti Password Baru</td>
                <td width="1%">:</td>
                <td><input type="text" id="bpass_log_user" style="width:200px;"/>
                </td>  
            </tr>
		   
		   <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_password();">Simpan</a>
		       <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_password();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>