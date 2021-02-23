<script type="text/javascript" src="<?php echo base_url();?>script/js/jquery-1.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>script/js/jquery.scrollTo.js"></script>

<script>

$(document).ready(function() {

	//Speed of the slideshow
	var speed = 5000;
	
	//You have to specify width and height in #slider CSS properties
	//After that, the following script will set the width and height accordingly
	$('#mask-gallery, #gallery li').width($('#slider').width());	
	$('#gallery').width($('#slider').width() * $('#gallery li').length);
	$('#mask-gallery, #gallery li, #mask-excerpt, #excerpt li').height($('#slider').height());
	
	//Assign a timer, so it will run periodically
	var run = setInterval('newsscoller(0)', speed);	
	
	$('#gallery li:first, #excerpt li:first').addClass('selected');

	//Pause the slidershow with clearInterval
	$('#btn-pause').click(function () {
		clearInterval(run);
		return false;
	});

	//Continue the slideshow with setInterval
	$('#btn-play').click(function () {
		run = setInterval('newsscoller(0)', speed);	
		return false;
	});
	
	//Next Slide by calling the function
	$('#btn-next').click(function () {
		newsscoller(0);	
		return false;
	});	

	//Previous slide by passing prev=1
	$('#btn-prev').click(function () {
		newsscoller(1);	
		return false;
	});	
	
	//Mouse over, pause it, on mouse out, resume the slider show
	$('#slider').hover(
	
		function() {
			clearInterval(run);
		}, 
		function() {
			run = setInterval('newsscoller(0)', speed);	
		}
	); 	
	
});


function newsscoller(prev) {

	//Get the current selected item (with selected class), if none was found, get the first item
	var current_image = $('#gallery li.selected').length ? $('#gallery li.selected') : $('#gallery li:first');
	var current_excerpt = $('#excerpt li.selected').length ? $('#excerpt li.selected') : $('#excerpt li:first');

	//if prev is set to 1 (previous item)
	if (prev) {
		
		//Get previous sibling
		var next_image = (current_image.prev().length) ? current_image.prev() : $('#gallery li:last');
		var next_excerpt = (current_excerpt.prev().length) ? current_excerpt.prev() : $('#excerpt li:last');
	
	//if prev is set to 0 (next item)
	} else {
		
		//Get next sibling
		var next_image = (current_image.next().length) ? current_image.next() : $('#gallery li:first');
		var next_excerpt = (current_excerpt.next().length) ? current_excerpt.next() : $('#excerpt li:first');
	}

	//clear the selected class
	$('#excerpt li, #gallery li').removeClass('selected');
	
	//reassign the selected class to current items
	next_image.addClass('selected');
	next_excerpt.addClass('selected');

	//Scroll the items
	$('#mask-gallery').scrollTo(next_image, 400);		
	$('#mask-excerpt').scrollTo(next_excerpt, 400);					
	
}



</script>

<style>


.scroll{
	width: 100%;
  	height: 250px;
  	overflow:auto;
  	font-family:arial;
	font-size:14px;
	border: none;
}	

#slider {

	/* You MUST specify the width and height */
	width:400px;
	height:186px;
	position:relative;	
	overflow:hidden;
}

#mask-gallery {
	
	overflow:hidden;	
}

#gallery {
	
	/* Clear the list style */
	list-style:none;
	margin:0;
	padding:0;
	
	z-index:0;
	
	/* width = total items multiply with #mask gallery width */
	width:1900px;
	overflow:hidden;
}

	#gallery li {

		
		/* float left, so that the items are arrangged horizontally */
		float:left;
	}


#mask-excerpt {
	
	/* Set the position */
	position:absolute;	
	top:0;
	left:0;
	/*z-index:500;*/
	z-index:0;
	
	/* width should be lesser than #slider width */
	width:100px;
	overflow:hidden;	
	

}
	
#excerpt {
	/* Opacity setting for different browsers */
	filter:alpha(opacity=60);
	-moz-opacity:0.6;  
	-khtml-opacity: 0.6;
	opacity: 0.6;  
	
	/* Clear the list style */
	list-style:none;
	margin:0;
	padding:0;
	
	/* Set the position */
	z-index:10;
	position:absolute;
	top:0;
	left:0;
	
	/* Set the style */
	width:100px;
	background-color:#000;
	overflow:hidden;
	font-family:arial;
	font-size:10px;
	color:#fff;	
}

	#excerpt li {
		padding:5px;
	}
	


.clear {
	clear:both;	
}

#gallery2 {
	
	/* Clear the list style 
	list-style:none;
	margin:0;
	padding:0;
	
	z-index:0;*/
	
	/* width = total items multiply with #mask gallery width */
	width:200px;
	overflow:hidden;
}
	#gallery2 li {
		/* float left, so that the items are arrangged horizontally */
		float:left;
	}

</style>

<?php
$kd_skpd = $this->session->userdata('kdskpd');
$user	= $this->session->userdata('pcUser');

?>



<div id="content" align="center">
<h1>Selamat Datang,<br /> <?php echo $this->session->userdata('Display_name'); ?>, skpd </h1>
<table border="0" width="920">



<tr> 
	
	<td width="700" align="justify" valign="top">
		<h1>Pengumuman :</h1>
	<div class="scroll">
		<?php 
		$sqlpengumuman="SELECT * from ms_pengumuman where aktif='1' and kd_skpd='$kd_skpd' order by id DESC";
	 	$sqlpmmn=$this->db->query($sqlpengumuman);
        foreach ($sqlpmmn->result() as $rowpmmn)
        {
        $judul 	=$rowpmmn->judul;
        $isi	=$rowpmmn->isi;
        $file 	=$rowpmmn->file;
        $status =$rowpmmn->status;
        
        ?>
        <p>
		
			
			<?php if($status=='1'){
	  ?>
	  				<h2><font color=red> [UPDATE] <?php echo $judul; ?> <br /></font></h2>
	  			<?php
	  		}else{
	  			?>
	  				<h2><font color=black><?php echo $judul; ?> <br /></font></h2>
	  			<?php

	  		}
	    echo $isi; ?>
	  </br>

	  <?php if($file=='' || $file=='-' || $file==null){
	  ?>
	  				
	  			<?php
	  		}else{
	  			?>
	  				<a href="<?php echo $file; ?>" download> <button type="primary"><i class="fa fa-download"> Download </i></button> </a>
	  			<?php

	  		}
	   ?>
	  	
	  	
	  
	  </br>
		
		</p>
        <?php

        }		

		?>
	</div>
	</td>
	<td width="220" valign="center" align="right">
		<img src="<?php echo base_url();?>image/home.png" width="300px" alt=""/>
	</td>
</tr>
<tr>
<td>Unduh Modul : <a href="<?php echo base_url();?>download/Petunjuk Teknik Pengadaan Barang dan Jasa.pdf" target="_blank">Modul Pengadaan Barang Dan Jasa (PDF)</a><br />
	</td>
</tr>
</table>
<!--<table border="0">
  <tr >Sederhanakankan Kebentuk <>
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/rka/tambah_rka">
            <img src="<?php echo base_url();?>image/slide_img/1.png" width="100" height="86" alt=""/>
    </a>
    </td>
    <td width="250" valign="top" align="center">
     <a href="<?php echo base_url();?>index.php/rka/anggaran_kas">
            <img src="<?php echo base_url();?>image/slide_img/2.png" width="100" height="86" alt=""/>
     </a>
    </td>
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/tukd/sppls">
            <img src="<?php echo base_url();?>image/slide_img/3.png" width="100" height="86" alt=""/>
    </a>
    </td>
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/tukd/penerimaan">
            <img src="<?php echo base_url();?>image/slide_img/4.png" width="100" height="86" alt=""/>
    </a>
    </td>
  </tr>
  <tr >
    <td width="250" valign="top" align="center">INPUT/EDIT RKA
    </td>
    <td width="250" valign="top" align="center">ANGGARAN KAS
    </td>
    <td width="250" valign="top" align="center">INPUT SPP LS
    </td>
    <td width="250" valign="top" align="center">PENERIMAAN PENDAPATAN
    </td>
  </tr>
  <tr >
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/rka/tambah_rka_ubah">
            <img src="<?php echo base_url();?>image/slide_img/1_1.png" width="100" height="86" alt=""/>
     </a>
    </td>
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/rka/anggaran_kas_ubah">
            <img src="<?php echo base_url();?>image/slide_img/2.png" width="100" height="86" alt=""/>
    </a>
    </td>
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/tukd/spm">
            <img src="<?php echo base_url();?>image/slide_img/3_3.png" width="100" height="86" alt=""/>
    </a>
    </td>
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/tukd/sp2d_cair">
            <img src="<?php echo base_url();?>image/slide_img/5.png" width="100" height="86" alt=""/>
    </a>
    </td>
  </tr>
  <tr >
    <td width="250" valign="top" align="center">INPUT/EDIT RKA PERUBAHAN
    </td>
    <td width="250" valign="top" align="center">ANGGARAN KAS PERUBAHAN
    </td>
    <td width="250" valign="top" align="center">INPUT SPM LS
    </td>
    <td width="250" valign="top" align="center">PENCAIRAN SP2D
    </td>
  </tr>
  <tr >
    <td width="250" valign="top" align="center">
    <a href="<?php echo base_url();?>index.php/rka/pengesahan_dpa">
            <img src="<?php echo base_url();?>image/slide_img/1_2.png" width="100" height="86" alt=""/>
     </a>
    </td>
    <td width="250" valign="top" align="center">
   
    </td>
    <td width="250" valign="top" align="center">
    
    </td>
    <td width="250" valign="top" align="center">
    
    </td>
  </tr>
  <tr >
    <td width="250" valign="top" align="center">PENGESAHAN ANGGARAN
    </td>
    <td width="250" valign="top" align="center">
    </td>
    <td width="250" valign="top" align="center">
    </td>
    <td width="250" valign="top" align="center">
    </td>
  </tr>
</table>
-->
    
            
</div>
