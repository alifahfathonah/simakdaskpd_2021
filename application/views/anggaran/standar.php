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
    
    
    
	
       function tarik(){

            $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/servis/asb',
                        data: ({}),
                        dataType:"json",
                        success:function(data){
                            alert("BERHASIL");
                        }
                    });
                }); 
            

		} 
    
   </script>

</head>
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
<body>

<div id="content" align="center">
<br>
<br>
<br>
<br>
<br>
<button class="button" onclick="javascript:tarik();" style="padding:20px;cursor: pointer;">TARIK STANDAR BIAYA DAN STANDAR HARGA</button>
<br>
<br>
<br>
<br>
<br>
</div>


  	
</body>

</html>