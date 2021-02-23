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
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.maskedinput.js"></script>
     
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
  
  <script type="text/javascript">
    
  
       function simpan(){

            var user    = document.getElementById('username').value;
            var userbaru= document.getElementById('userbaru').value;
            var passlama= document.getElementById('passlama').value;
            var passbaru= document.getElementById('passbaru').value;
            var ulang   = document.getElementById('ulang').value;




            if(userbaru.length==0){
                var userbaru= user;
            }else if(userbaru.length<6){
                alert("Username minimal 6 karakter!");
                exit();                
            }

            if(passbaru!=ulang){
                alert("Password tidak cocok!");
                exit();
            }
            if(passbaru.length<6){
                alert("Password minimal 6 karakter!");
                exit();
            }
            $(document).ready(function(){
                /*cek password lama*/
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/cek_password',
                    data: ({passlama:passlama,user:user}),
                    dataType:"json",
                    success:function(data2){
                        var cek2 = data2;
                        if(cek2==0){
                            alert("Password Lama Salah!");
                            exit();
                        }else{
                            /*simpan*/
                            $.ajax({
                                type: "POST",
                                url: '<?php echo base_url(); ?>/index.php/master/update_password',
                                data: ({passbaru:passbaru,user:user,userbaru:userbaru,jenis:'password'}),
                                dataType:"json",
                                success:function(data3){
                                    if(data3==1){
                                        $("#passlama").attr("value","");
                                        $("#userbaru").attr("value","");
                                        $("#passbaru").attr("value","");
                                        $("#ulang").attr("value","");
                                        alert("Data telah diperbarui!");
                                    }else{
                                        alert("Gagal diperbarui");
                                    }
                                }
                            });

                        }
                    }

                });


            });   
           
       

    } 
   
    function simpan_user(){
            var user    = document.getElementById('username').value;
            var userbaru= document.getElementById('userbaru').value;
            var passlama= document.getElementById('passlama1').value;
            var passbaru= document.getElementById('passbaru').value;
            var ulang   = document.getElementById('ulang').value;

            if(userbaru.length==0){
                var userbaru= user;
            }else if(userbaru.length<6){
                alert("Username minimal 6 karakter!");
                exit();                
            }
            $(document).ready(function(){
                /*cek password lama*/
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/cek_password',
                    data: ({passlama:passlama,user:user}),
                    dataType:"json",
                    success:function(data2){
                        var cek2 = data2;
                        if(cek2==0){
                            alert("Password Lama Salah!");
                            exit();
                        }else{
                            /*simpan*/
                            $.ajax({
                                type: "POST",
                                url: '<?php echo base_url(); ?>/index.php/master/update_password',
                                data: ({passbaru:passbaru,user:user,userbaru:userbaru,jenis:'username'}),
                                dataType:"json",
                                success:function(data3){
                                    if(data3==1){
                                        $("#passlama").attr("value","");
                                        $("#userbaru").attr("value","");
                                        $("#passbaru").attr("value","");
                                        $("#ulang").attr("value","");
                                        alert("Data telah diperbarui!");
                                        location.replace("<?php echo base_url(); ?>welcome/login");
                                    }else{
                                        alert("Gagal diperbarui");
                                    }
                                }
                            });

                        }
                    }

                });


            });  




    }

    function cek_user(){
        var userbaru= document.getElementById('userbaru').value
        $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/cek_user',
                    data: ({userbaru:userbaru}),
                    dataType:"json",
                    success:function(data1){
                        var cek1 = data1;
                        if(cek1==1){
                            document.getElementById('notif').innerHTML="&nbsp; <label>Username telah digunakan</label>";
                            $('#simpan_user').hide();
                        }else{
                            document.getElementById('notif').innerHTML="";
                            $('#simpan_user').show();
                        }
                    }
                });
        });
    }
  
   </script>
<style>
.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  text-align: center;
  padding: 8px 16px;
  text-decoration: none;
  display: inline-block;
  transition-duration: 0.4s;
  cursor: pointer;
  border-radius: 3px;
  margin: 4px 2px;
}


.button:hover {
  background-color: #008CBA;
  color: white;
}

</style>
</head>
<body>

<div id="content"> 
    <div align="center">
    <p align="center">     

    <fieldset style="width:400px;border: 2px solid green; border-radius: 10px; margin-bottom: 10px">
        <legend>Ganti Username</legend>
        <table style="width:400px;" border="0">
            <tr>
                <td>Username</td>
                <td>: <input style="display: inline" class="input" type="text" disabled name="username" id="username" value="<?php echo $this->session->userdata('pcNama');?>"></td>
            </tr>
            <tr>
                <td>Username Baru</td>
                <td>: <input readonly style="display: inline" class="input" onkeyup="javascript:cek_user();" type="text" name="userbaru" id="userbaru"> <br>  <label id="notif"></label>
            </tr>
            <tr>
                <td>Password</td>
                <td>: <input style="display: inline" class="input" type="password" name="passlama1" id="passlama1"></td>
            </tr>
            <tr id="simpan_user" >
                <td></td>
                <td><button class="button-biru" onclick="javascript:simpan_user();"> SIMPAN USER BARU</button></td>
            </tr>
        </table>  
    </fieldset>
    <fieldset style="width:400px;border: 2px solid green; border-radius: 10px">
        <legend>Ganti Password</legend>
        <table style="width:400px;" border="0">
            <tr>
                <td>Password Lama</td>
                <td>: <input style="display: inline" class="input" type="password" name="passlama" id="passlama"></td>
            </tr>
            <tr>
                <td>Password Baru</td>
                <td>: <input style="display: inline" class="input" type="password" name="passbaru" id="passbaru"></td>
            </tr>
            <tr>
                <td>Ulangi Password</td>
                <td>: <input style="display: inline" class="input" type="password" name="ulang" id="ulang"></td>
            </tr>
            <tr>
                <td></td>
                <td><button class="button-biru" onclick="javascript:simpan();"> SIMPAN PASSWORD BARU</button></td>
            </tr>
        </table>  
    </fieldset>
    </p> 
    </div>   
</div>


</body>

</html>