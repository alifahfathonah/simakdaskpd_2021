<div id="content">
    	<h1><?php echo $page_title; ?> PA
        <span><a href="<?php echo site_url(); ?>/master/tambah_user_rup">Tambah</a></span>
        </h1>
		<?php echo form_open('master/cari_user', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>ID </th>
                <th>Nama</th>
                <th>KODE </th>
				<th>NAMA SKPD</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $user) : ?>
            <tr>
            	<td><?php echo $user->id_user; ?></td>
                <td><?php echo $user->nama; ?></td>
                <td><?php echo $user->kd_skpd; ?></td>
				<td><?php $nm = $this->db->query("select top 1 nm_skpd from ms_skpd where kd_skpd='$user->kd_skpd'")->row(); echo $nm->nm_skpd; ?></td>
                <td>
                
                 &nbsp;&nbsp;
            <a href="<?php echo site_url(); ?>/master/hapus_user_rup/<?php echo $user->id_user; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a>
                 
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>