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
            height: 650,
            width: 1200,
            modal: true,
            autoOpen:false,
        });
        
        $("#spm_gu").hide();
        $("#spm_tu").hide();
        $("#spm_lsbrg").hide();            
        $("#spm_lsbtl").hide();
        $("#spm_uang").hide(); 
        
        });    
     
  
        $(function(){
   	     $('#tgl_con').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            	//return y+'-'+m+'-'+d;
            }
        });
        
        $('#tglspm1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            	//return y+'-'+m+'-'+d;
            }
        });
        
        
        $('#tglspm2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            	//return y+'-'+m+'-'+d;
            }
        });
        
   	});
	
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>index.php/bud_validasi_spm/load_validasi_spm',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",
        rowStyler: function(index,row){
                    if (row.tot_spm >= 2){
                        return 'background-color:#ff471a;';
                        }else if (row.status == 1){
                    return 'background-color:#03d3ff;';
                    }
                },                        
        columns:[[
    	    {field:'nm_skpd',
    		title:'SKPD',
    		width:50,
            align:"left"},
            {field:'no_spm',
            title:'no. SPM',
            width:30,
            align:"center"},
            {field:'sttval',
            title:'Status',
            width:20,
            align:"center"}
            

        ]],
        onSelect:function(rowIndex,rowData){
                    kd_skpdedit= rowData.kd_skpd;
                    nm_skpdedit= rowData.nm_skpd;
                    no_spmedit = rowData.no_spm;
                    no_sppedit = rowData.no_spp;
                    nmrekanedit = rowData.nmrekan;
                    pimpinanedit = rowData.pimpinan;
                    no_rekedit = rowData.no_rek;
                    npwpedit = rowData.npwp;
                    bankedit = rowData.bank;
                    keperluanedit = rowData.keperluan;
                    no_tagihedit = rowData.no_tagih;
                    ketedit = rowData.ket;
                    ket_bastedit = rowData.ket_bast;
                    jns_spp =  rowData.jns_spp;
                    tgl_terima = rowData.tgl_terima;
                    tgl_setuju = rowData.tgl_setuju;
                    ket_val = rowData.ket_val;
                    stt_validasi = rowData.stt_validasi;
                    tot_spm= rowData.tot_spm;
                    $("#terbilang").attr("value",rowData.terbilang);
                    $("#nilaispm").attr("value",rowData.nilai);
                    vspm1 = rowData.spm1;        
                    vspm2 = rowData.spm2;
                    vspm3 = rowData.spm3;
                    vspm4 = rowData.spm4;
                    vspm5 = rowData.spm5;
                    vspm6 = rowData.spm6;
                    vspm7 = rowData.spm7;
                    vspm8 = rowData.spm8;
                    vspm9 = rowData.spm9;
                    vspm10 = rowData.spm10;
                    vspm11 = rowData.spm11;
                    vspm12 = rowData.spm12;
                    vspm13 = rowData.spm13;
                    vspm14 = rowData.spm14;
                    vspm15 = rowData.spm15;
                    vspm16 = rowData.spm16;
                    vspm17 = rowData.spm17;
                    vspm18 = rowData.spm18;
                    vspm19 = rowData.spm19;
                    vspm20 = rowData.spm20;
                    vspm21 = rowData.spm21;
                    vspm22 = rowData.spm22;
                    vspm23 = rowData.spm23;
                    vspm24 = rowData.spm24;
                    vspm25 = rowData.spm25;
                    vspm26 = rowData.spm26;
                    vspm27 = rowData.spm27;
                    vspm28 = rowData.spm28;
                    vspm29 = rowData.spm29;
                    vspm30 = rowData.spm30;
                    vspm31 = rowData.spm31;
                    vspm32 = rowData.spm32;
                    vspm33 = rowData.spm33;
                    vspm34 = rowData.spm34;
                    vspm35 = rowData.spm35;
                    vspm36 = rowData.spm36;
                    vspm37 = rowData.spm37;
                    vspm38 = rowData.spm38;
                    vspm39 = rowData.spm39;
                    vspm40 = rowData.spm40;
                    vspm41 = rowData.spm41;
                    vspm42 = rowData.spm42;
                    vspm43 = rowData.spm43;
                    vspm44 = rowData.spm44;
                    vspm45 = rowData.spm45;
                    cek_data(no_sppedit,kd_skpdedit);
                    getedit(kd_skpdedit,nm_skpdedit,no_spmedit,no_sppedit,pimpinanedit,no_rekedit,npwpedit,bankedit,keperluanedit,nmrekanedit,no_tagihedit,ketedit,ket_bastedit,tgl_terima,tgl_setuju,ket_val,stt_validasi,jns_spp,vspm1,vspm2,vspm3,vspm4,vspm5,vspm6,vspm7,vspm8,vspm9,vspm10,vspm11,vspm12,vspm13,vspm14,vspm15,vspm16,vspm17,vspm18,vspm19,vspm20,vspm21,vspm22,vspm23,vspm24,vspm25,vspm26,vspm27,vspm28,vspm29,vspm30,vspm31,vspm32,vspm33,vspm34,vspm35,vspm36,vspm37,vspm38,vspm39,vspm40,vspm41,vspm42,vspm43,vspm44,vspm45,tot_spm);
            
                                       
        },
        onDblClickRow:function(rowIndex,rowData){

           lcidx = rowIndex;
           judul = 'Edit Data ANGGARAN'; 
           edit_data();   
        }
        
        });
            

        $('#bankedit').combogrid({  
        panelWidth:250,  
        url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
        idField:'kd_bank',  
        textField:'nama_bank',
        mode:'remote',  
        fitColumns:true,  
            columns:[[  
                {field:'kd_bank',title:'Kd Bank',width:70},  
                {field:'nama_bank',title:'Nama',width:180}
                ]],  
            onSelect:function(rowIndex,rowData){
//$("#nama_bank").attr("value",rowData.nama_bank);
                    }   
            });   
        });

       
 
	function getedit(kd_skpdedit,nm_skpdedit,no_spmedit,no_sppedit,pimpinanedit,no_rekedit,npwpedit,bankedit,keperluanedit,nmrekanedit,no_tagihedit,ketedit,ket_bastedit,tgl_terima,tgl_setuju,ket_val,stt_validasi,jns_spp,vspm1,vspm2,vspm3,vspm4,vspm5,vspm6,vspm7,vspm8,vspm9,vspm10,vspm11,vspm12,vspm13,vspm14,vspm15,vspm16,vspm17,vspm18,vspm19,vspm20,vspm21,vspm22,vspm23,vspm24,vspm25,vspm26,vspm27,vspm28,vspm29,vspm30,vspm31,vspm32,vspm33,vspm34,vspm35,vspm36,vspm37,vspm38,vspm39,vspm40,vspm41,vspm42,vspm43,vspm44,vspm45,tot_spm){
            kosongkan(); 
            $("#kdskpdedit").attr("Value",kd_skpdedit);
            $("#nmskpdedit").attr("Value",nm_skpdedit);
            $("#spmedit").attr("value",no_spmedit);
            $("#sppedit").attr("value",no_sppedit);
            $("#norekedit").attr("value",no_rekedit);
            $("#pimpinanedit").attr("value",pimpinanedit);
            $("#npwpedit").attr("value",npwpedit);
            $("#rekanedit").attr("value",nmrekanedit);
            $("#keperluanedit").attr("value",keperluanedit);  
            $("#bankedit").combogrid("setValue",bankedit);
            $("#no_tagihedit").attr("value",no_tagihedit);
            $("#ketedit").attr("value",ketedit); 
            $("#ket_bastedit").attr("value",ket_bastedit);               
            $("#tglspm1").datebox("setValue",tgl_terima);
            $("#tglspm2").datebox("setValue",tgl_setuju);
            $("#ketspm").attr("value",ket_val); 
            $("#sttspm").attr("value",stt_validasi);   
            
            
            if(tot_spm=='2'){
            alert('ADA DATA SPP YANG GANDA !! SILAHKAN REFRESH HALAMAN !!');
            
			}
            
            if(vspm1!=0){document.getElementById('spm1').checked = true;}
            if(vspm2!=0){document.getElementById('spm2').checked = true;}
            if(vspm3!=0){document.getElementById('spm3').checked = true;}
            if(vspm4!=0){document.getElementById('spm4').checked = true;}
            if(vspm5!=0){document.getElementById('spm5').checked = true;}
            if(vspm6!=0){document.getElementById('spm6').checked = true;}
            if(vspm7!=0){document.getElementById('spm7').checked = true;}
            if(vspm8!=0){document.getElementById('spm8').checked = true;}
            if(vspm9!=0){document.getElementById('spm9').checked = true;}
            if(vspm10!=0){document.getElementById('spm10').checked = true;}
            if(vspm11!=0){document.getElementById('spm11').checked = true;}
            if(vspm12!=0){document.getElementById('spm12').checked = true;}
            if(vspm13!=0){document.getElementById('spm13').checked = true;}
            if(vspm14!=0){document.getElementById('spm14').checked = true;}
            if(vspm15!=0){document.getElementById('spm15').checked = true;}
            if(vspm16!=0){document.getElementById('spm16').checked = true;}
            if(vspm17!=0){document.getElementById('spm17').checked = true;}
            if(vspm18!=0){document.getElementById('spm18').checked = true;}
            if(vspm19!=0){document.getElementById('spm19').checked = true;}
            if(vspm20!=0){document.getElementById('spm20').checked = true;}
            if(vspm21!=0){document.getElementById('spm21').checked = true;}
            if(vspm22!=0){document.getElementById('spm22').checked = true;}
            if(vspm23!=0){document.getElementById('spm23').checked = true;}
            if(vspm24!=0){document.getElementById('spm24').checked = true;}
            if(vspm25!=0){document.getElementById('spm25').checked = true;}
            if(vspm26!=0){document.getElementById('spm26').checked = true;}
            if(vspm27!=0){document.getElementById('spm27').checked = true;}
            if(vspm28!=0){document.getElementById('spm28').checked = true;}
            if(vspm29!=0){document.getElementById('spm29').checked = true;}
            if(vspm30!=0){document.getElementById('spm30').checked = true;}
            if(vspm31!=0){document.getElementById('spm31').checked = true;}
            if(vspm32!=0){document.getElementById('spm32').checked = true;}
            if(vspm33!=0){document.getElementById('spm33').checked = true;}
            if(vspm34!=0){document.getElementById('spm34').checked = true;}
            if(vspm35!=0){document.getElementById('spm35').checked = true;}
            if(vspm36!=0){document.getElementById('spm36').checked = true;}
            if(vspm37!=0){document.getElementById('spm37').checked = true;}
            if(vspm38!=0){document.getElementById('spm38').checked = true;}
            if(vspm39!=0){document.getElementById('spm39').checked = true;}
            if(vspm40!=0){document.getElementById('spm40').checked = true;}
            if(vspm41!=0){document.getElementById('spm41').checked = true;}
            if(vspm42!=0){document.getElementById('spm42').checked = true;}
            if(vspm43!=0){document.getElementById('spm43').checked = true;}
            if(vspm44!=0){document.getElementById('spm44').checked = true;}
            if(vspm45!=0){document.getElementById('spm45').checked = true;}
            
            if(jns_spp=='1'){
                $("#spm_gu").show();
                $("#spm_tu").hide();
                $("#spm_lsbrg").hide();            
                $("#spm_lsbtl").hide();
                $("#spm_uang").hide();
            }else if(jns_spp=='2'){
                $("#spm_gu").show();
                $("#spm_tu").hide();
                $("#spm_lsbrg").hide();            
                $("#spm_lsbtl").hide();
                $("#spm_uang").hide();            
            }else if(jns_spp=='3'){
                $("#spm_gu").hide();
                $("#spm_tu").show();
                $("#spm_lsbrg").hide();            
                $("#spm_lsbtl").hide();
                $("#spm_uang").hide();            
            }else if(jns_spp=='4'){
                $("#spm_gu").hide();
                $("#spm_tu").hide();
                $("#spm_lsbrg").hide();            
                $("#spm_lsbtl").show();
                $("#spm_uang").hide();            
            }else if(jns_spp=='5'){
                $("#spm_gu").hide();
                $("#spm_tu").hide();
                $("#spm_lsbrg").hide();            
                $("#spm_lsbtl").show();
                $("#spm_uang").hide();            
            }else if(jns_spp=='6'){
                $("#spm_gu").hide();
                $("#spm_tu").hide();
                $("#spm_lsbrg").show();            
                $("#spm_lsbtl").hide();
                $("#spm_uang").hide();            
            }else if(jns_spp=='7'){
                $("#spm_gu").show();
                $("#spm_tu").hide();
                $("#spm_lsbrg").hide();            
                $("#spm_lsbtl").hide();
                $("#spm_uang").hide();
            }
                               
        } 
    
    function cek_skpd(init){
        var init_sp2d = init;
        $(function(){
            $('#sskpd').combogrid({  
            panelWidth:900,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/bud_validasi_spm/spm_skpd/'+init_sp2d,  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:200},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                xskpd = rowData.kd_skpd;
                $("#nmskpd").attr("value",rowData.nm_skpd);
                validate_jenis();                
            }
            });
            });
    }

    function kosongkan(){
            $("#kdskpdedit").attr("Value",'');
            $("#nmskpdedit").attr("Value",'');
            $("#spmedit").attr("value",'');
            $("#sppedit").attr("value",'');
            $("#norekedit").attr("value",'');
            $("#pimpinanedit").attr("value",'');
            $("#npwpedit").attr("value",'');
            $("#rekanedit").attr("value",'');
            $("#keperluanedit").attr("value",'');  
            $("#bankedit").combogrid("setValue",'');
            $("#no_tagihedit").attr("value",'');
            $("#ketedit").attr("value",''); 
            $("#ket_bastedit").attr("value",''); 
    }
	
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Terima SPP & SPM !';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("no").disabled=true;
        }    
        
    
    function jenis_sp2d(){
        var jns_ang = document.getElementById('jns_anggaran').value;
        cek_skpd(jns_ang);        
     }

     function validate_jenis(){
        var jns_ang = document.getElementById('jns_anggaran').value;
        var sskpd = xskpd;
        var krit = '';


        $(function(){ 
                $('#dg').edatagrid({
                  url: '<?php echo base_url(); ?>/index.php/bud_validasi_spm/load_validasi_spm/'+sskpd+'/'+jns_ang,
                  queryParams:({kriteria_init:krit})
                });        
               });
     }


      

        function perbaiki_data(){
        var kdskpd = document.getElementById('kdskpdedit').value;
        var spmedit = document.getElementById('spmedit').value;
        var tglterima = $('#tglspm1').datebox('getValue');
        var tglsetuju = $('#tglspm2').datebox('getValue');
        var ketspm  = document.getElementById('ketspm').value; 
        var statusspm = document.getElementById('sttspm').value;
        
        var dspm1 = document.getElementById('spm1').checked; if (dspm1==true){dspm1 = "1";}else{dspm1 = "0";}        
        var dspm2 = document.getElementById('spm2').checked; if (dspm2==true){dspm2 = "2";}else{dspm2 = "0";}
        var dspm3 = document.getElementById('spm3').checked; if (dspm3==true){dspm3 = "3";}else{dspm3 = "0";}
        var dspm4 = document.getElementById('spm4').checked; if (dspm4==true){dspm4 = "4";}else{dspm4 = "0";}
        var dspm5 = document.getElementById('spm5').checked; if (dspm5==true){dspm5 = "5";}else{dspm5 = "0";}
        var dspm6 = document.getElementById('spm6').checked; if (dspm6==true){dspm6 = "6";}else{dspm6 = "0";}
        var dspm7 = document.getElementById('spm7').checked; if (dspm7==true){dspm7 = "7";}else{dspm7 = "0";}
        var dspm8 = document.getElementById('spm8').checked; if (dspm8==true){dspm8 = "8";}else{dspm8 = "0";}
        var dspm9 = document.getElementById('spm9').checked; if (dspm9==true){dspm9 = "9";}else{dspm9 = "0";}
        var dspm10 = document.getElementById('spm10').checked; if (dspm10==true){dspm10 = "10";}else{dspm10 = "0";}
        var dspm11 = document.getElementById('spm11').checked; if (dspm11==true){dspm11 = "11";}else{dspm11 = "0";}
        var dspm12 = document.getElementById('spm12').checked; if (dspm12==true){dspm12 = "12";}else{dspm12 = "0";}
        var dspm13 = document.getElementById('spm13').checked; if (dspm13==true){dspm13 = "13";}else{dspm13 = "0";}
        var dspm14 = document.getElementById('spm14').checked; if (dspm14==true){dspm14 = "14";}else{dspm14 = "0";}
        var dspm15 = document.getElementById('spm15').checked; if (dspm15==true){dspm15 = "15";}else{dspm15 = "0";}
        var dspm16 = document.getElementById('spm16').checked; if (dspm16==true){dspm16 = "16";}else{dspm16 = "0";}
        var dspm17 = document.getElementById('spm17').checked; if (dspm17==true){dspm17 = "17";}else{dspm17 = "0";}
        var dspm18 = document.getElementById('spm18').checked; if (dspm18==true){dspm18 = "18";}else{dspm18 = "0";}
        var dspm19 = document.getElementById('spm19').checked; if (dspm19==true){dspm19 = "19";}else{dspm19 = "0";}
        var dspm20 = document.getElementById('spm20').checked; if (dspm20==true){dspm20 = "20";}else{dspm20 = "0";}
        var dspm21 = document.getElementById('spm21').checked; if (dspm21==true){dspm21 = "21";}else{dspm21 = "0";}
        var dspm22 = document.getElementById('spm22').checked; if (dspm22==true){dspm22 = "22";}else{dspm22 = "0";}
        var dspm23 = document.getElementById('spm23').checked; if (dspm23==true){dspm23 = "23";}else{dspm23 = "0";}
        var dspm24 = document.getElementById('spm24').checked; if (dspm24==true){dspm24 = "24";}else{dspm24 = "0";}
        var dspm25 = document.getElementById('spm25').checked; if (dspm25==true){dspm25 = "25";}else{dspm25 = "0";}
        var dspm26 = document.getElementById('spm26').checked; if (dspm26==true){dspm26 = "26";}else{dspm26 = "0";}
        var dspm27 = document.getElementById('spm27').checked; if (dspm27==true){dspm27 = "27";}else{dspm27 = "0";}
        var dspm28 = document.getElementById('spm28').checked; if (dspm28==true){dspm28 = "28";}else{dspm28 = "0";}
        var dspm29 = document.getElementById('spm29').checked; if (dspm29==true){dspm29 = "29";}else{dspm29 = "0";}
        var dspm30 = document.getElementById('spm30').checked; if (dspm30==true){dspm30 = "30";}else{dspm30 = "0";}
        var dspm31 = document.getElementById('spm31').checked; if (dspm31==true){dspm31 = "31";}else{dspm31 = "0";}
        var dspm32 = document.getElementById('spm32').checked; if (dspm32==true){dspm32 = "32";}else{dspm32 = "0";}
        var dspm33 = document.getElementById('spm33').checked; if (dspm33==true){dspm33 = "33";}else{dspm33 = "0";}
        var dspm34 = document.getElementById('spm34').checked; if (dspm34==true){dspm34 = "34";}else{dspm34 = "0";}
        var dspm35 = document.getElementById('spm35').checked; if (dspm35==true){dspm35 = "35";}else{dspm35 = "0";}
        var dspm36 = document.getElementById('spm36').checked; if (dspm36==true){dspm36 = "36";}else{dspm36 = "0";}
        var dspm37 = document.getElementById('spm37').checked; if (dspm37==true){dspm37 = "37";}else{dspm37 = "0";}
        var dspm38 = document.getElementById('spm38').checked; if (dspm38==true){dspm38 = "38";}else{dspm38 = "0";}
        var dspm39 = document.getElementById('spm39').checked; if (dspm39==true){dspm39 = "39";}else{dspm39 = "0";}
        var dspm40 = document.getElementById('spm40').checked; if (dspm40==true){dspm40 = "40";}else{dspm40 = "0";}
        var dspm41 = document.getElementById('spm41').checked; if (dspm41==true){dspm41 = "41";}else{dspm41 = "0";}
        var dspm42 = document.getElementById('spm42').checked; if (dspm42==true){dspm42 = "42";}else{dspm42 = "0";}
        var dspm43 = document.getElementById('spm43').checked; if (dspm43==true){dspm43 = "43";}else{dspm43 = "0";}
        var dspm44 = document.getElementById('spm44').checked; if (dspm44==true){dspm44 = "44";}else{dspm44 = "0";}
        var dspm45 = document.getElementById('spm45').checked; if (dspm45==true){dspm45 = "45";}else{dspm45 = "0";}
        
        if(tglterima==''){
            alert('Tanggal Terima SPM belum dipilih');
            exit();
        }
        
        if(ketspm==''){
            alert('Keterangan SPM belum diisi');
            exit();
        }
        
        if(statusspm=='0'){
            alert('Status SPM belum dipilih');
            exit();
        }
        
       
     
     
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({kdskpd:kdskpd,spmedit:spmedit,tglterima:tglterima,tglsetuju:tglsetuju,ketspm:ketspm,statusspm:statusspm,dspm1:dspm1,dspm2:dspm2,dspm3:dspm3,dspm4:dspm4,dspm5:dspm5,dspm6:dspm6,dspm7:dspm7,dspm8:dspm8,dspm9:dspm9,dspm10:dspm10,dspm11:dspm11,dspm12:dspm12,dspm13:dspm13,dspm14:dspm14,dspm15:dspm15,dspm16:dspm16,dspm17:dspm17,dspm18:dspm18,dspm19:dspm19,dspm20:dspm20,dspm21:dspm21,dspm22:dspm22,dspm23:dspm23,dspm24:dspm24,dspm25:dspm25,dspm26:dspm26,dspm27:dspm27,dspm28:dspm28,dspm29:dspm29,dspm30:dspm30,dspm31:dspm31,dspm32:dspm32,dspm33:dspm33,dspm34:dspm34,dspm35:dspm35,dspm36:dspm36,dspm37:dspm37,dspm38:dspm38,dspm39:dspm39,dspm40:dspm40,dspm41:dspm41,dspm42:dspm42,dspm43:dspm43,dspm44:dspm44,dspm45:dspm45}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/bud_validasi_spm/simpan_validasi_spm",
            success:function(asg3){
                if (asg3==2){                               
                    alert('Data Gagal Tersimpan');
                    document.getElementById('spmedit').value=asg3;                  
                }else{                  
                    document.getElementById('spmedit').value=asg3;
                    alert('Data Berhasil Tersimpan');
                   keluar2();
                   validate_jenis();
                }
            }
         });
        });        
        }
        

        function keluar2(){
        $("#dialog-modal").dialog('close');
    }     
    
    function openWindow(url) {
        var kode = document.getElementById('spmedit').value;
        var no =kode.split("/").join("123456789");
        window.open(url+'/'+no+'/PERKIRAAN SP2D', '_blank');
        window.focus();
    }
    
     function cek2($cetak,$jxx){
        
        urlx='';
        //alert($jxx);
        if($jxx<=1){
        urlx="<?php echo site_url(); ?>/tukd/preview_cetakan_val_spm/"+$cetak+'/Report-val'
        }else{
            //alert('x');
        urlx="<?php echo site_url(); ?>/tukd/preview_cetakan_val_sp2d/"+$cetak+'/Report-SP2D'    
        }
        openWindows( urlx );
    }
        
        
        function openWindows( urlx ){
        
            lc = '';
      window.open(urlx+lc,'_blank');
      window.focus();
      
     }  
     
      function cek_data(no_sppedit,kd_skpdedit){
           
		   var no_spp = no_sppedit;
		   var kd_skpd= kd_skpdedit;
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/bud_validasi_spm/select_data_untuk_cek',
        		type: "POST",
        		dataType:"json",
                data      : ({no_spp:no_spp,kd_skpd:kd_skpd}),                         
        		success:function(data){
                                        var has  = data.hasil;
                                        
										tombol_cek_selisih(has);
        							  }                                     
        	});        
       }
	   
	   function tombol_cek_selisih(has){  
    if (has=='1'){
           // $('#ctk').linkbutton('disable');
			$('#save').linkbutton('disable');
            document.getElementById("pcek").innerHTML="REALISASI MELEBIHI ANGGARAN...!!!";
            //status_apbd = '1';
            
     } else {
            //$('#ctk').linkbutton('enable');
            $('#save').linkbutton('enable');
            document.getElementById("pcek").innerHTML="";
            //status_apbd = '0';
            
     }
    }
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">VALIDASI TERIMA SPP & SPM</a></b></u></h3>
    <div>
    <p align="left" >
        <a>&nbsp;&ensp;*) Pilih SKPD dan Jenis Beban terlebih Dahulu !</a>   
    </p>
    <table>    
    <tr>
        <td>Pilih Jenis Beban</td>
        <td>:</td>
        <td>    
            <select class="textarea" name="jns_anggaran" id="jns_anggaran" onchange="javascript:jenis_sp2d();" style="height: 27px; width:160px;">    
            <option value="0">...Pilih Jenis... </option>   
            <option value="1">UP</option>
            <option value="2">GU</option>
            <option value="3">TU</option>
            <option value="4">LS Gaji</option>
            <option value="5">LS PPKD</option>
            <option value="6">LS Barang Jasa</option>
            <option value="7">GU Nihil</option>
            </select>
        </td>
    </tr>
    <tr>
        <td >S K P D&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&nbsp;&nbsp;</td>
        <td>:&nbsp;</td>
        <td>&nbsp;<input id="sskpd" name="sskpd" style="width:160px;border: 0;" />
        <input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;  " /></td>
    </tr>

        <tr>
           <td width="10%">Cetak List Antrian SPM</td>
           <td width="1%">:</td>
           <td width="40%"> 
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(0,'1');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(1,'1');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(2,'1');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>
           </td>    
        </tr>
        <tr>
           <td width="10%">Cetak List SP2D</td>
           <td width="1%">:</td>
           <td width="40%"> 
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(0,'2');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(1,'2');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(2,'2');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>
           </td>    
        </tr>
    </table>
        
        <table id="dg" title="LIST SPM YANG BELUM DI SP2D-KAN" style="width:1024px;height:450px;" >  
        </table>
 
     <p>
        <table border="0" width="37%">
            <tr>
                <td colspan="2">Ket Warna:</td>
            </tr>
          
			<tr>
                <td>Orange</td>
                <td>: Data SPP Double</td>
            </tr>
        </table>
    </p>
    </div>   

</div>

</div>


<div id="dialog-modal" title="">
    <fieldset>
    <table align="center" style="width:100%;" border="0">    
    <p id="pcek" style="font-size: x-large;color: red;"></p>
    <tr>
    <td width="60%">    
     <table align="center" style="width:100%;" border="0">
            <tr>
            <td width="110px">Kode SKPD</td>
            <td>:<input id="kdskpdedit" name="kdskpdedit" style="width: 100px;" readonly="true"/><input id="nmskpdedit" name="nmskpdedit" style="width: 400px;"readonly="true" /></td>
        </tr>
        <tr>
            <td width="110px">NO Tagih</td>
            <td>:<input id="no_tagihedit" class="input" name="no_tagihedit" style="width: 270px; display: inline;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">NO SPM</td>
            <td>:<input id="spmedit" class="input" name="spmedit" style="width: 270px; display: inline;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">NO SPP</td>
            <td>:<input id="sppedit" class="input" name="sppedit" style="width: 270px; display: inline;" readonly="true"/></td>
        </tr>
         <tr>
            <td width="110px">Rekanan</td>
            <td>:<input id="rekanedit" name="rekanedit" class="input" style="width: 500px; display: inline;" /></td>
        </tr>
       <tr>
            <td width="110px">Pimpinan</td>
            <td>:<input id="pimpinanedit" name="pimpinanedit" class="input"  style="width: 270px; display: inline;" /></td>
        </tr>
        <tr>
            <td width="110px">no rek</td>
            <td>:<input id="norekedit" name="norekedit" class="input"  style="width: 270px; display: inline;"/></td>
        </tr>
        <tr>
            <td width="110px">NPWP</td>
            <td>:<input id="npwpedit" class="input"  name="npwpedit" style="width: 270px; display: inline;"/>
        Bank
            :<input id="bankedit" name="bankedit" style="width: 130px;" />
        </td>
        </tr>
            <td width="110px">Keperluan</td>
            <td><textarea name="keperluanedit" class="textarea" id="keperluanedit" rows="2" style="width: 500px;"></textarea></td>
        </tr>
        <tr>
            <td width="110px">Ket. BAST</td>
            <td><textarea name="ket_bastedit" class="textarea" id="ket_bastedit" rows="2" style="width: 500px;"></textarea></td>
        </tr>
        </tr>
            <td width="110px">Nilai</td>
            <td>Rp. <input name="nilaispm" id="nilaispm" readonly style="border-style: none ;width: 250px; display: inline;"></td>
        </tr> 
        </tr>
            <td width="110px" align="right"><i>Terbilang</i><br> &nbsp; </td>
            <td> <i><textarea name="terbilang" id="terbilang" readonly style="border-style: none ;width: 500px;"></textarea></i></td>
        </tr>       
        </table>

        <table bgcolor="#AABBBB" width="100%">
        <tr>
        <td colspan="2" align="center">
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>/tukd/cetak_perkiraan_sp2d');return false;">Cetak Perkiraan SP2D</a>         
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>/tukd/cetak_lamp_perkiraan_sp2d');return false;">Cetak Lampiran Perkiraan SP2D</a></td>                 
        </tr>        
        <tr>        
        <td></td>
        <td>
                <table>
                    <tr>
                        <td>Tanggal Terima SPM</td>
                        <td>: <input id="tglspm1" name="tglspm1" style="width: 100px;" /></td>
                        <td rowspan="2"><textarea placeholder="Keterangan Kontrol SPM" name="ketspm" id="ketspm" rows="3" style="width: 250px;"></textarea></td>
                    </tr>
                    <tr>
                        <td>Tanggal Disetujui SPM</td>
                        <td>: <input id="tglspm2" name="tglspm2" style="width: 100px;" /></td>                        
                    </tr>
                </table>           
              </td>        
        </tr> 
        <tr>        
        <td>Status SPM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
        <td>
                <table>
                    <tr>
                        <td><select id="sttspm" name="sttspm" style="width: 250px;">
                                <option value="0">...STATUS SPM...</option>                                
                                <option value="1">BERKAS LENGKAP DAN DISETUJUI</option>
                                <option value="2">BERKAS SPP SPM DITUNDA</option>
                                <option value="3">BERKAS SPP SPM DIBATALKAN</option>
                            </select> 
                        </td>
                        <td>
                        &nbsp;
                        <button class="button-biru" onclick="javascript:perbaiki_data();"> Simpan Terima SPM</button>
                        <button class="button-abu" onclick="javascript:keluar2();"> Keluar</button>
                    </td>
                    </tr>                    
                </table>           
              </td>        
        </tr>            
        <tr>
        </tr>
        </table>
        
       </td>
       <td bgcolor="#F8F8FF" width="40%">
            <div id="spm_gu">
            <table width="100%">
            <tr><td><b>KELENGKAPAN DOKUMEN PENGAJUAN SPM UP/GU</b></td></tr>            
            <tr><td><input type="checkbox" name="spm1" id="spm1" value="1"/>Surat Pernyataan Pernyataan SPP-GU</td></tr>
            <tr><td><input type="checkbox" name="spm2" id="spm2" value="2"/>Surat Pengantar SPP-GU</td></tr>
            <tr><td><input type="checkbox" name="spm3" id="spm3" value="3"/>Ringkasan GU</td></tr>
            <tr><td><input type="checkbox" name="spm4" id="spm4" value="4"/>Salinan SPD (GU)</td></tr> 
            <tr><td><input type="checkbox" name="spm5" id="spm5" value="5"/>Surat Pengesahan Laporan SPJ atas Pengunaan</td></tr>
            <tr><td><input type="checkbox" name="spm6" id="spm6" value="6"/>Rincian SPP-GU</td></tr> 
            <tr><td><input type="checkbox" name="spm7" id="spm7" value="7"/>Lampiran Lainnya (GU) </td></tr>               
            </table>
            </div>
            
            <div id="spm_tu">
            <table width="100%">
            <tr><td><b>KELENGKAPAN DOKUMEN PENGAJUAN SPM TU</b></td></tr>            
            <tr><td><input type="checkbox" name="spm8" id="spm8" value="8"/>Surat Pernyataan Pengajuan SPP-TU</td></tr>
            <tr><td><input type="checkbox" name="spm9" id="spm9" value="9"/>Surat Pengantar SPP-TU</td></tr>
            <tr><td><input type="checkbox" name="spm10" id="spm10" value="10"/>Ringkasan TU</td></tr>
            <tr><td><input type="checkbox" name="spm11" id="spm11" value="11"/>Salinan SPD (TU)</td></tr> 
            <tr><td><input type="checkbox" name="spm12" id="spm12" value="12"/>Surat Keterangan Pengelasan TU</td></tr>
            <tr><td><input type="checkbox" name="spm13" id="spm13" value="13"/>Lampiran Lainnya (TU)</td></tr> 
            <tr><td><input type="checkbox" name="spm14" id="spm14" value="14"/>Rekening Koran (TU)</td></tr>               
            </table>
            </div>
            
            <div id="spm_lsbrg">
            <table width="100%">
            <tr><td><b>KELENGKAPAN DOKUMEN PENGAJUAN SPM LS BARANG JASA</b></td></tr>            
            <tr><td><input type="checkbox" name="spm15" id="spm15" value="15"/>Surat Pengantar SPP LS Barang Jasa</td></tr>
            <tr><td><input type="checkbox" name="spm16" id="spm16" value="16"/>Ringkasan SPP</td></tr>
            <tr><td><input type="checkbox" name="spm17" id="spm17" value="17"/>Salinan SPD</td></tr>
            <tr><td><input type="checkbox" name="spm18" id="spm18" value="18"/>Rincian SPP LS</td></tr> 
            <tr><td><input type="checkbox" name="spm19" id="spm19" value="19"/>Surat Rekomendasi dari SKPD</td></tr>
            <tr><td><input type="checkbox" name="spm20" id="spm20" value="20"/>SPP disertai Faktur Pajak PPN dan PPh/SSBP</td></tr> 
            <tr><td><input type="checkbox" name="spm21" id="spm21" value="21"/>Surat Perjanjian Kerja (SPK)</td></tr> 
            <tr><td><input type="checkbox" name="spm22" id="spm22" value="22"/>BA. Penyelesaian Pekerjaan</td></tr> 
            <tr><td><input type="checkbox" name="spm23" id="spm23" value="23"/>BA. Serah Terima</td></tr> 
            <tr><td><input type="checkbox" name="spm24" id="spm24" value="24"/>BA. Pembayaran</td></tr> 
            <tr><td><input type="checkbox" name="spm25" id="spm25" value="25"/>BA. Pemeriksaan</td></tr>  
            <tr><td><input type="checkbox" name="spm26" id="spm26" value="26"/>Kwitansi Bermaterai</td></tr>  
            <tr><td><input type="checkbox" name="spm27" id="spm27" value="27"/>Surat Jaminan Bank / Lembaga Keuangan Non Bank</td></tr>  
            <tr><td><input type="checkbox" name="spm28" id="spm28" value="28"/>Dokumen Lainnya yang disyaratkan</td></tr>
            <tr><td><input type="checkbox" name="spm29" id="spm29" value="29"/>Surat Angkutan / Konosemen apabila Pekerjaan Dilaksanakan diluar Wilayah Kerja</td></tr>
            <tr><td><input type="checkbox" name="spm30" id="spm30" value="30"/>Surat Pemeritahuan Potongan Denda Keterlambatan Pekerjaan</td></tr>
            <tr><td><input type="checkbox" name="spm31" id="spm31" value="31"/>Foto, Buku, Dokumentasi Kemajuan Pekerjaan</td></tr>
            <tr><td><input type="checkbox" name="spm32" id="spm32" value="32"/>Potongan JAMSOSTEK</td></tr>
            <tr><td><input type="checkbox" name="spm33" id="spm33" value="33"/>Khusus Konsultan Harga menggunakan Biaya Personil (Billing Rate) dengan Melampirkan BA. Kemajuan Pekerjaan dengan Bukti Kehadiran dari Konsultan, dan Bukti Penyewaan / Pembelian Alat Sesuai Rincian dalam Surat Penawaran</td></tr>
            </table>
            </div>
            
            <div id="spm_lsbtl">
            <table width="100%">
            <tr><td><b>KELENGKAPAN DOKUMEN PENGAJUAN SPM LS BELANJA TIDAK LANGSUNG</b></td></tr>            
            <tr><td><input type="checkbox" name="spm34" id="spm34" value="34"/>Surat Pengantar SPP LS Gaji</td></tr>
            <tr><td><input type="checkbox" name="spm35" id="spm35" value="35"/>Ringkasan SPP LS Gaji</td></tr>
            <tr><td><input type="checkbox" name="spm36" id="spm36" value="36"/>Rincian SPP LS Gaji</td></tr>
            </table>
            </div> 
            
            <div id="spm_uang">
            <table width="100%">
            <tr><td><b>KELENGKAPAN DOKUMEN PENGAJUAN SPM (UANG MUKA)</b></td></tr>            
            <tr><td><input type="checkbox" name="spm37" id="spm37" value="37"/>Surat Pengantar SPP (Uang Muka)</td></tr>
            <tr><td><input type="checkbox" name="spm38" id="spm38" value="38"/>Ringkasan</td></tr>
            <tr><td><input type="checkbox" name="spm39" id="spm39" value="39"/>Salinan SPD</td></tr>
            <tr><td><input type="checkbox" name="spm40" id="spm40" value="40"/>Rincian SPP</td></tr>
            <tr><td><input type="checkbox" name="spm41" id="spm41" value="41"/>Jaminan Uang Muka</td></tr>
            <tr><td><input type="checkbox" name="spm42" id="spm42" value="42"/>BPJS Ketenaga Kerjaan / JAMSOSTEK</td></tr>
            <tr><td><input type="checkbox" name="spm43" id="spm43" value="43"/>RIncian Penggunaan Uang Muka</td></tr>
            <tr><td><input type="checkbox" name="spm44" id="spm44" value="44"/>SPP disertai Faktur Pajak PPN dan PPh</td></tr>
            <tr><td><input type="checkbox" name="spm45" id="spm45" value="45"/>Surat Perjanjian Kerja (SPK)</td></tr>
            </table>
            </div>            
       </td>
       </tr> 
       </table>
  </fieldset>
</div>

  	
</body>

</html>