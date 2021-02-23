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
        <h1><?php echo $page_title; ?> </h1>
        <?php echo form_open('master/cari_user', array('class' => 'basic')); ?>
        <!--Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />-->
        <?php echo form_close(); ?> 

        <?php if (  $this->session->flashdata('notify') <> "" ) : ?>
            <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
        <?php if( $this->session->userdata('kdskpd') =="4.02.01.00" ) {
            $oke="hidden";
        } else {
            $oke="";
        } 
        ?>  
         
        <img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico" /> STATUS TERBUKA <br>
        <img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico" /> STATUS TERKUNCI   
        <table class="narrow" width="100%" >
            <tr>
                <th>Kode SKPD</th>
                <th>Nama SKPD</th>
                
                <th <?php echo $oke?>><center>ANGGARAN MURNI</center></th>
                <th><center>Angkas Murni</center></th>
                <th <?php echo $oke?>><center>ANGGARAN GESER</center></th>
                <th><center> Angkas Geser</center></th>
                <th <?php echo $oke?>><center>ANGGARAN UBAH</center></th>
                <th><center> Angkas Ubah</center></th>
                <th <?php echo $oke?>><center>LOCKAKUN</center></th>
            </tr>
            <?php foreach($list->result() as $user) : ?>
            <tr >
                <td><?php echo $user->kd_skpd; ?></td>
                <td><?php echo $user->nm_skpd; ?></td>

<!-- KUNCI INPUT RKA MURNI -->

                <?php if (  $user->kunci_murni == "0" ) : ?>  
                    <td <?php echo $oke?>><!-- Status Terbuka &nbsp;  -->
                    <a href="<?php echo site_url(); ?>master/akses_input_murni/<?php echo $user->kd_skpd; ?>/1" title="Status terbuka!! Klik untuk mengunci"><img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico"  <?php echo $oke?> /></a>
                    </td>
                <?php endif; ?>
                <?php if (  $user->kunci_murni == "1" ) : ?>
                    <td <?php echo $oke?>><!-- Status Terkunci &nbsp; -->
                    <a href="<?php echo site_url(); ?>master/akses_input_murni/<?php echo $user->kd_skpd; ?>/0" title="Status terkunci!! Klik untuk membuka"><img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico"   <?php echo $oke?>/></a>
                </td>
                <?php endif; ?>
<!-- KUNCI INPUT ANGKAS MURNI -->
            <?php if (  $user->kunci_angkas_m == "0" ) : ?>   
                <td><!-- Status Terbuka &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_angkas_murni/<?php echo $user->kd_skpd; ?>/1" title="Status terbuka!! Klik untuk mengunci"><img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico" /></a>
                    <?php endif; ?> 
                    <?php if (  $user->kunci_angkas_m == "1" ) : ?>
                        <td><!-- Status Terkunci &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_angkas_murni/<?php echo $user->kd_skpd; ?>/0" title="Status terkunci!! Klik untuk membuka"><img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico" /></a>
                    <?php endif; ?>     
                        </td>
                </td>
 
<!-- KUNCI INPUT RKA PERGESERAN -->
            <?php if (  $user->kunci_geser == "0" ) : ?>   
                <td <?php echo $oke?>><!-- Status Terbuka &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_geser/<?php echo $user->kd_skpd; ?>/1" title="Status terbuka!! Klik untuk mengunci"><img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico" /></a>
                     </td>
                    <?php endif; ?> 
                <?php if (  $user->kunci_geser == "1" ) : ?>
                        <td <?php echo $oke?>><!-- Status Terkunci &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_geser/<?php echo $user->kd_skpd; ?>/0" title="Status terkunci!! Klik untuk membuka"><img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico" /></a>
                    <?php endif; ?>     
                       
                </td>

<!-- KUNCI INPUT ANGKAS PERGESERAN -->
            <?php if (  $user->kunci_angkas_g == "0" ) : ?>   
                <td><!-- Status Terbuka &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_angkas_geser/<?php echo $user->kd_skpd; ?>/1" title="Status terbuka!! Klik untuk mengunci"><img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico" /></a>
                    <?php endif; ?> 
                    <?php if (  $user->kunci_angkas_g == "1" ) : ?>
                        <td><!-- Status Terkunci &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_angkas_geser/<?php echo $user->kd_skpd; ?>/0" title="Status terkunci!! Klik untuk membuka"><img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico" /></a>
                    <?php endif; ?>     
                        </td>
                </td>
<!-- KUNCI INPUT RKA UBAH -->
            <?php if (  $user->kunci_ubah == "0" ) : ?>   
                <td <?php echo $oke?>><!-- Status Terbuka &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_ubah/<?php echo $user->kd_skpd; ?>/1" title="Status terbuka!! Klik untuk mengunci"><img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico" /></a>
                    <?php endif; ?> 
                    <?php if (  $user->kunci_ubah == "1" ) : ?>
                        <td <?php echo $oke?>><!-- Status Terkunci &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_ubah/<?php echo $user->kd_skpd; ?>/0" title="Status terkunci!! Klik untuk membuka"><img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico" /></a>
                    <?php endif; ?>     
                        </td>
                </td>
<!-- KUNCI INPUT ANGKAS PERGESERAN -->
            <?php if (  $user->kunci_angkas_u == "0" ) : ?>   
                <td><!-- Status Terbuka &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_angkas_ubah/<?php echo $user->kd_skpd; ?>/1" title="Status terbuka!! Klik untuk mengunci"><img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico" /></a>
                    <?php endif; ?> 
                    <?php if (  $user->kunci_angkas_u == "1" ) : ?>
                        <td><!-- Status Terkunci &nbsp; -->
                        <a href="<?php echo site_url(); ?>master/akses_input_angkas_ubah/<?php echo $user->kd_skpd; ?>/0" title="Status terkunci!! Klik untuk membuka"><img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico" /></a>
                    <?php endif; ?>     
                        </td>
                </td>

<!-- KUNCI KUNCI AKUN -->
                <?php if (  $user->kunci == "1" ) : ?>  
                    <td <?php echo $oke?>><!-- Status Terbuka &nbsp;  -->
                    <a href="<?php echo site_url(); ?>master/kunci_akun/<?php echo $user->kd_skpd; ?>/0" title="Status terkunci!! Klik untuk mrmbuka"><img src="<?php echo base_url(); ?>assets/images/icon/kunci.ico"  <?php echo $oke?> /></a>
                    </td>
                <?php endif; ?>
                <?php if (  $user->kunci == "0" ) : ?>
                    <td <?php echo $oke?>><!-- Status Terkunci &nbsp; -->
                    <a href="<?php echo site_url(); ?>master/kunci_akun/<?php echo $user->kd_skpd; ?>/1" title="Status terbuka!! Klik untuk mengunci"><img src="<?php echo base_url(); ?>assets/images/icon/unlock.ico"   <?php echo $oke?>/></a>
                </td>
                <?php endif; ?>


            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2">Kunci Keseluruhan</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
            </tr>
        </table>
      

    </div>