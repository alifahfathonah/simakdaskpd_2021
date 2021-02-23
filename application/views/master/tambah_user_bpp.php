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
     
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/> 
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
  <script type="text/javascript">
    
    var kode = '';
    var giat = ''; 
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
    function hapus_spasi(){
        var username= document.getElementById('username').value;
        var user = username.replace(" ", "");
        document.getElementById("username").value = user; 
    } 

    function simpan(){
        var password1= document.getElementById('password1').value;
        var password2= document.getElementById('password2').value;
        var namaskpd= document.getElementById('namaskpd').value;
        var username= document.getElementById('username').value;

        if(password1!=password2){
            alert("Password tidak sama!"); exit();
        }
        $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/utilitas/simpan_user',
                        data: ({pswd:password1, user:username, nama:namaskpd}),
                        dataType:"json",
                        success:function(data){
                            status = data;
                            if(status=='1'){
                                alert('Tersimpan');
                                kosong();
                                exit();
                            }else{
                                alert('Gagal');
                                exit();
                            }
                        }
                    });
        }); 
    }
   </script>

</head>
<body>

<div id="content"> 
<fieldset style="border: 5px solid black; border-radius: 10px ">
<legend>Tambah User BPP</legend>
    <div align="center"> 
    <table style="width:1024px;" border="0">
            <tr>
                <td width="30%">Nama </td>
                <td width="1%">:</td>
                <td><input class="input" type="text" id="namaskpd" style="width:200px;"/>
                </td>
            </tr>
            <tr>
                <td width="30%">Username </td>
                <td width="1%">:</td>
                <td>
                  <input onkeyup="javascript:hapus_spasi()" class="input" type="text" id="username" style="width:200px;"/>
                </td>
            </tr>
            <tr>
                <td width="30%">Password</td>
                <td width="1%">:</td>
                <td><input class="input" type="password" id="password1" style="width:200px;"/>
                    </td>
            </tr>
            <tr>
                <td width="30%">Ulangi Password</td>
                <td width="1%">:</td>
                <td><input class="input" type="password" id="password2" style="width:200px;"/>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center"> <button class="button-biru" onclick="javascript:simpan()">Simpan</button></td>
            </tr>
    </table>    

    </div>   


</div>
</fieldset>
</body>

</html>