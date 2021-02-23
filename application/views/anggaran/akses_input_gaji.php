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
     
        
     function aktif(skpd){
            $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/anggaran_murni/kunci_gaji',
                        data: ({skpd:skpd}),
                        dataType:"json",
                        success:function(data){

                        }
                    });
                });  
    }      


    
  
   </script>
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
</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><b><a href="#" id="section1">AKSES INPUT GAJI</a></b></h3>
    <div>

    <table width="100%">
        <tr>
            <td align="center" bgcolor="#cccccc"><b>KODE</td>
            <td align="center" bgcolor="#cccccc"><b>NAMA</td>
            <td align="center" bgcolor="#cccccc"><b>[KUNCI] | [BUKA]</td>
        </tr>
    <?php foreach($isi as $oke) : ?>
        <tr>
            <td align="center"><?php echo $oke['kd_skpd']; ?></td>
            <td><?php echo $oke['nm_skpd']; ?></td>
            <td align="center"><?php echo $oke['status']; ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
    </div>   

</div>

</div>
  	
</body>

</html>