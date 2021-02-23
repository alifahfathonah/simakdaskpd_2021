  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
	<script type="text/javascript">
    $(document).ready(function() {         
		get_no();
        });
    
    $(function(){
        $('#pcskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpdall',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:580}    
            ]],
            onSelect:function(rowIndex,rowData){
                urusan = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);
               // validate_skpd();
                
            }  
        }); 
      });

    function get_no(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/master/no_urut_user',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
					$("#id_user").attr("value",data.no_urut);
					//$("#id_user_tersedia").attr("value",data.no_urut);
        		}                                     
     });}
</script>
<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/user_rup_pa">Kembali</a></span></h1>

	<?php echo form_open('master/tambah_user_rup_pa', array('class' => 'basic')); ?>
    <table class="form">
       <!-- <tr>
    <td><label>Nomor id user Tersedia</label><br />
            <input name="id_user_tersedia" type="text" id="id_user_tersedia" size="10" readonly="true" />
			
            </td>
        </tr>-->
    
    	<tr>
    <td><label>id user tersedia</label><br />
            <input name="id_user" type="text" id="id_user" value="<?php echo set_value('id_user'); ?>" size="10" />
			<?php echo form_error('id_user'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama PPK</label><br />
            <input name="nama" type="text" id="nama" value="<?php echo set_value('nama'); ?>" size="80" />
            <?php echo form_error('nama'); ?>
            </td>
        </tr>
        <tr>
            <td><label>User name </label><br />
            <input name="user_name" type="text" id="user_name" value="<?php echo set_value('user_name'); ?>" size="40" />
            <?php echo form_error('user_name'); ?>
            </td>
        </tr>
        <tr>
            <td><label>password</label><br />
            <input name="password" type="text" id="password" value="<?php echo set_value('password'); ?>" size="40" />
            <?php echo form_error('password'); ?>
            </td>
        </tr>
        <tr>
            <td><!--<label>type user</label>--><br />
            <input name="type" type="hidden" id="type" value="2" size="40" />
            <?php echo form_error('type'); ?>
            </td>
        </tr>
        <tr>
            <td><label>SKPD</label>
            <input id="pcskpd" name="pcskpd" style="width: 100px;" /> <input id="nm_skpd" name="nm_skpd" style="width:600px;border: 0;"/>
            </td>
        </tr>			
        <tr>
            <td>
            <input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" />
            </td>
        </tr>
    </table>
   
</div>