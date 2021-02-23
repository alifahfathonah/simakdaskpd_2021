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
    
    var kode     = '';
    var giat     = '1';
    var nomor    = '';
    var cid      = 0 ;
    var plrek    = '';
    var lcstatus = '';
    var kdrek    = '';    
    var curut    = '';
    var tahun_anggaran = '';
    var bulan = '';
    var rekening='';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $("#dialog-modal").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
         $("#dialog-modal_t").dialog({
            height: 500,
            width: 800,
            modal: true,
            autoOpen:false
        });
        $("#dialog-modal_cetak").dialog({
            height: 200,
            width: 400,
            modal: true,
            autoOpen:false
        });
        $("#dialog-modal_edit").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
        get_skpd(); 
		get_tahun();
        get_urut();
        });    
     
     //datagrid list sts
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd_cms/load_belum_sts',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true", 
        rowStyler:function(index,row){                
        if ((row.status_up==1 && row.status_val==1)){
		   return 'background-color:#B0E0E6';
        }else if ((row.status_up==1)){
		   return 'background-color:#98FB98;';
        }
        
		},                       
        columns:[[
    	    {field:'no_sts',
    		title:'Nomor STS',
    		width:50},
            {field:'tgl_sts',
    		title:'Tanggal',
    		width:15},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:15,
            align:"left"},
            {field:'total',
    		title:'Total',
    		width:20,
            align:"right"},
            {field:'keterangan',
    		title:'Uraian',
    		width:45,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor     = rowData.no_sts;
          tgl       = rowData.tgl_sts;
          kode      = rowData.kd_skpd;
          lckdbank  = rowData.kd_bank;
          lckdgiat  = rowData.kd_kegiatan;
          lcket     = rowData.keterangan;
          lcjnskeg  = rowData.jns_trans;
          lcrekbank = rowData.rek_bank;
		  lno_terima= rowData.no_terima;
          lctotal   = rowData.total;
		  jenis_status = rowData.sumber;
          sbank = rowData.bank;
          rekwal    = rowData.rekening_awal;      
            
          snmrek_awal    = rowData.nm_rekening_tujuan;
          sbank_tjn = rowData.bank_tujuan;         
          srek_tjn  = rowData.rekening_tujuan;
          stt_up    = rowData.status_up;  
          stt_val   = rowData.status_val;   
          nmrekk    = rowData.nmrek;       
          get(nomor,tgl,kode,lckdbank,lckdgiat,lcket,lcjnskeg,lcrekbank,lctotal,jenis_status,lno_terima,sbank,rekwal,snmrek_awal,sbank_tjn,srek_tjn,stt_up,stt_val,nmrekk);   
          //load_detail(nomor);        
          lcstatus  = 'edit';
        },
        onDblClickRow:function(rowIndex,rowData){
          nomor     = rowData.no_sts;
          tgl       = rowData.tgl_sts;
          kode      = rowData.kd_skpd;
          lckdbank  = rowData.kd_bank;
          lckdgiat  = rowData.kd_kegiatan;
          lcket     = rowData.keterangan;
          lcjnskeg  = rowData.jns_trans;
          lcrekbank = rowData.rek_bank;
		  lno_terima = rowData.no_terima;
          lctotal   = rowData.total;
		  jenis_status = rowData.sumber;
          sbank = rowData.bank;
          rekwal    = rowData.rekening_awal;         
          snmrek_awal    = rowData.nm_rekening_tujuan;
          sbank_tjn = rowData.bank_tujuan;         
          srek_tjn  = rowData.rekening_tujuan;
          stt_up    = rowData.status_up;  
          stt_val   = rowData.status_val;  
          nmrekk    = rowData.nmrek;        
          get(nomor,tgl,kode,lckdbank,lckdgiat,lcket,lcjnskeg,lcrekbank,lctotal,jenis_status,lno_terima,sbank,rekwal,snmrek_awal,sbank_tjn,srek_tjn,stt_up,stt_val,nmrekk);   
          
          load_detail(nomor);                    
            section2();   
        }
        });
        
        $('#dg_tetap').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_tetap_sts/'+kode+'/'+plrek,
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"false",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'ck',
    		title:'Pilih',
    		width:5,
            align:"center",
            checkbox:true                
            },
    	    {field:'no_tetap',
    		title:'Nomor Tetap',
    		width:10,
            align:"center"},
            {field:'tgl_tetap',
    		title:'Tanggal',
    		width:5,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:5,
            align:"center"}
        ]]
        });
        
        
        $('#dg1').edatagrid({  
            toolbar:'#toolbar',
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",            
            nowrap:"true",
            onSelect:function(rowIndex,rowData){                    
                    idx = rowIndex;
                    lnnilai = rowData.rupiah;
            },                                                     
            columns:[[
                {field:'id',
        		title:'ID',    		
                hidden:"true"},
                {field:'no_sts',
        		title:'No STS',    		
                hidden:"true"},                
        	    {field:'kd_rek5',
        		title:'Nomor Rekening',
                width:1},
                {field:'nm_rek',
        		title:'Nama Rekening',
                width:3},                
                {field:'rupiah',
        		title:'Rupiah',
                align:'right',
                width:1},
                {field:'kd_rek6',
        		title:'Nomor Rekening',
                width:1,hidden:true}               
            ]],
           onDblClickRow:function(rowIndex,rowData){
           idx = rowIndex; 
           lcrekedt   = rowData.kd_rek5;
           lcnmrekedt = rowData.nm_rek;
           lcnilaiedt = rowData.rupiah; 
           get_edt(lcrekedt,lcnmrekedt,lcnilaiedt); 
        }
        });      
    

        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
                onSelect: function(date){
            	var m = date.getMonth()+1;
					bulan = m;
                    get_hasil();
				}   
        });
        
     		$('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd_pa_ppk',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });  

			$('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd_cek/bp',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });
        
        $('#rekening_awal').combogrid({            
            url:"<?php echo base_url(); ?>index.php/tukd_cms/cari_rekening_pend",
            panelWidth:150,
            idField:'rek_bend',
            textField:'rek_bend',
            columns:[[
                {field:'rek_bend',title:'Rekening Bendahara',width:130}
            ]]
        });
        
        $('#rekening_tujuan').combogrid({            
            url:"<?php echo base_url(); ?>index.php/tukd_cms/cari_rekening_tujuan_kasda/4",
            panelWidth:370,
            idField:'rekening',
            textField:'rekening',
            columns:[[
                {field:'rekening',title:'Rekening',width:130},
                {field:'nm_rekening',title:'Nama',width:230}
            ]],
            onSelect:function(rowIndex,rowData){
            $("#nm_rekening_tujuan").attr("Value",rowData.nm_rekening);
            $("#kd_bank_tujuan").combogrid("setValue",rowData.nmbank);                
          }
        });
        
        $('#kd_bank_tujuan').combogrid({            
            url:"<?php echo base_url(); ?>index.php/tukd_cms/cari_bank",
            panelWidth:150,
            idField:'nama',
            textField:'nama',
            columns:[[
                {field:'nama',title:'Bank',width:130}
            ]]
        });
        
        $('#tanggalkas').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
			onSelect: function(date){
				var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
        $("#tanggal").datebox("setValue",y+'-'+m+'-'+d);
		
		}
        });
        
        $('#tglvoucher').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();    
                	return y+'-'+m+'-'+d;
                }
         });
        
          /*  $('#rek_sts').combogrid({  
           panelWidth : 550,  
           idField    : 'kd_rek5',  
           textField  : 'kd_rek5',  
           mode       : 'remote',                     
           columns    : [[  
		       {field:'kd_rek5',title:'Kode Rek LRA',width:90},  
               {field:'kd_rek',title:'Kode Rek LO',width:90},
			   {field:'nm_rek',title:'Uraian Rinci',width:350},
			   ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek_sts").attr("value",rowData.nm_rek.toUpperCase());                           
               rekening = rowData.kd_rek5;               
              }      
            });*/
            
        
        $('#rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tetap/'+kode,             
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:140},  
               {field:'nm_rek',title:'Uraian',width:700},
              ]],
              
               onSelect:function(rowIndex,rowData){
                plrek = rowData.kd_rek5;
               $("#nmrek1").attr("value",rowData.nm_rek.toUpperCase());
               $("#dg_tetap").edatagrid({url: '<?php echo base_url(); ?>/index.php/tukd/load_tetap_sts/'+kode+'/'+plrek});
              }    
            });                    
            
          $('#cmb_sts').combogrid({  
           panelWidth:700,  
           idField:'no_sts',  
           textField:'no_sts',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_sts',  
           columns:[[  
               {field:'no_sts',title:'Nomor STS',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               nomor = rowData.no_sts;               
           } 
       });
       
	   //combo box no_terima
	   $('#noterima').combogrid({
					//url: '<?php echo base_url(); ?>index.php/tukd_cms/list_no_terima',
					panelWidth:1000,
                    idField:'no_terima',  
                    textField:'no_terima',
                    mode:'remote',  
                    fitColumns:true,                    
                    columns:[[  
                        {field:'no_terima',title:'No Terima',width:120},
						{field:'tgl_terima',title:'Tanggal',width:100},
						{field:'kd_rek5',title:'Kode Rek',width:75},
                        {field:'nilai',title:'Total',align:'right',width:120},                          
                        {field:'keterangan',title:'Ket',align:'left',width:900}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
					no_terima  = rowData.no_terima;
					tgl_terima = rowData.tgl_terima;
					kd_rek5    = rowData.kd_rek5;
                    kd_rek6    = rowData.kd_rek6;
					kd_skpd    = rowData.kd_skpd;
					nilai      = rowData.nilai;
				    
                       kdrek=kd_rek5; 
                       var nilai1 = angka(nilai);
					   var lstotal = angka(document.getElementById('jumlahtotal').value);					
                       total = number_format(lstotal+nilai1,2,'.',',');					
					   tampil_no_terima(no_terima,tgl_terima,kd_rek5,kd_skpd,nilai,kd_rek6);	
                                     
                    }   
                });
  
          
        $('#cmb_rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek/'+kode+'/'+giat,             
           columns:[[
			   {field:'no_terima',title:'No Terima',width:140},
               {field:'kd_rek5',title:'Kode Rekening',width:140},  
               {field:'nm_rek',title:'Uraian',width:700},
              ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek);
               $("#nilai").attr("value",rowData.nilai);
			   $("#no_terima").attr("value",rowData.no_terima);
              }    
            });
  
                     
        $('#giat').combogrid({
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd,
           url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1_pend/'+'4',             
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               giat = rowData.kd_kegiatan;
               $("#nmgiat").attr("value",rowData.nm_kegiatan.toUpperCase());
               //validate_rek(kode);  
               load_terimas();                                    
           }
              
        });
		
		$('#pengirim').combogrid({
           panelWidth:700,  
           idField:'kd_pengirim',  
           textField:'kd_pengirim',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_pengirim',             
           columns:[[  
               {field:'kd_pengirim',title:'Kode Pengirim',width:140},  
               {field:'nm_pengirim',title:'Nama Pengirim',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               kd_pengirim = rowData.kd_pengirim;
               $("#nmpengirim").attr("value",rowData.nm_pengirim);                                      
           }
              
        });
        
    });  
    
	function tampil_no_terima(no_terima,tgl_terima,kd_rek5,kd_skpd,nilai,kd_rek6){
		
			$('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var p=0;p<rows.length;p++){
                cno_terima  = rows[p].no_terima;
                if (cno_terima==no_terima) {
					alert('Nomor ini sudah ada di LIST');
					exit();
				}
			}
			$('#dg1').datagrid('selectAll');			
			jgrid     = rows.length ; 
			pidx = jgrid + 1 ;
					$('#dg1').edatagrid('appendRow',{no_terima:no_terima,tgl_terima:tgl_terima,kd_rek5:kd_rek5,nilai:nilai,idx:pidx,kd_rek6:kd_rek6});
                    $('#dg1').edatagrid('unselectAll');
					$('#jumlahtotal').attr('value',total);

    }
	
    function load_terimas(){
        //combo box no_terima
	   $('#noterima').combogrid({
					url: '<?php echo base_url(); ?>index.php/tukd_cms/list_no_terima',
					panelWidth:1000,
                    idField:'no_terima',  
                    textField:'no_terima',
                    mode:'remote',  
                    fitColumns:true,                    
                    columns:[[  
                        {field:'no_terima',title:'No Terima',width:120},
						{field:'tgl_terima',title:'Tanggal',width:100},
						{field:'kd_rek5',title:'Kode Rek',width:75},
                        {field:'nilai',title:'Total',align:'right',width:120},                          
                        {field:'keterangan',title:'Ket',align:'left',width:900}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
					no_terima  = rowData.no_terima;
					tgl_terima = rowData.tgl_terima;
					kd_rek5    = rowData.kd_rek5;
                    kd_rek6    = rowData.kd_rek6;
					kd_skpd    = rowData.kd_skpd;
					nilai      = rowData.nilai;
					
                    kdrek=kd_rek5; 
                       var nilai1 = angka(nilai);
					   var lstotal = angka(document.getElementById('jumlahtotal').value);					
                       total = number_format(lstotal+nilai1,2,'.',',');					
					   tampil_no_terima(no_terima,tgl_terima,kd_rek5,kd_skpd,nilai,kd_rek6);
                                          
                    }   
                });
    }
    
      function validate_rek(){
	  	$(function(){
        $('#rek_sts').combogrid({  
           panelWidth : 550,  
           idField    : 'kd_rek5',  
           textField  : 'kd_rek5',  
           mode       : 'remote',
           url        : '<?php echo base_url(); ?>index.php/tukd/ambil_rek_tetap/'+kode,             
           columns    : [[  
		       {field:'kd_rek5',title:'Kode Rek LRA',width:90},  
               {field:'kd_rek',title:'Kode Rek LO',width:90},
			   {field:'nm_rek',title:'Uraian Rinci',width:350}
              ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek_sts").attr("value",rowData.nm_rek.toUpperCase());                           
               rekening = rowData.kd_rek5;
               get_hasil();
              }    
            });
	  	    });
		} 
    
    function get_skpd()
    {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);     
                                        kode = data.kd_skpd;   								  								
                                        lckode='4';
                                        get_rek(kode); 
                                        $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1_pend/'+lckode+'/'+kode});
        							  }                                     
        	});
    } 
	 function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        } 
    
    function get_urut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd_cms/config_sts',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			curut = data.nomor;
                    $("#nomor").attr("value",curut);
                    
        			}                                     
        	});
             
        }   
        
    function get_hasil(){       
       var a1 = document.getElementById('jns_sts').value;       
       var a2 = "STS";
       var a3 = kode;      
       var a4 = rekening;
       var a5 = bulan;
       var a6 = tahun_anggaran;
       var hasil = "/"+a1+"/"+a2+"/"+a3+"/"+a5+"/"+a6;
       $("#nomor_tambahan").attr("value",hasil);
    }    
    
    function runjns(){
        get_hasil();
    }
    
    function get_rek(kode){
            $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_t_sts/'+kode});
        }
    
    function openWindow(url)
        {
        var ttd1= $('#ttd1').combogrid('getValue');
		var ttd2= $('#ttd2').combogrid('getValue');
		ttd1=ttd1.split(" ").join("a");
		ttd2=ttd2.split(" ").join("a");
        var no =nomor.split("/").join("123456789");
        window.open(url+'/'+no+'/'+ttd1+'/'+ttd2+'/SURAT TANDA SETORAN', '_blank');
        window.focus();
        }     

    function loadgiat(){
        var lcjnsrek=document.getElementById("jns_trans").value;
        alert(lcjnsrek);
         $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1_pend/'+lcjnsrek});  
    }
    
    function load_detail(kk){        

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd_cms/load_dsts',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                   
                                $.each(data,function(i,n){
                                id = n['id'];    
                                kdrek = n['kd_rek5']; 
                                kdrek6 = n['kd_rek6'];                                                                   
                                lnrp = n['rupiah'];    
                                lcnmrek = n['nm_rek'];
                                lcnosts = n['no_sts'];
								lcnoterima = n['no_terima'];
                                $('#dg1').datagrid('appendRow',{id:id,no_sts:lcnosts,no_terima:lcnoterima,kd_rek5:kdrek,nilai:lnrp,nm_rek:lcnmrek,kd_rek6:kdrek6});
                         
                                });   
                                 
                }
            });
           });  
  
         set_grid();
                           
    }
 
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
    }
    
    function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
         set_grid();      
     }
       
     
    function get(nomor,tgl,kode,lckdbank,lckdgiat,lcket,lcjnskeg,lcrekbank,lctotal,jenis_status,lno_terima,sbank,rekwal,snmrek_awal,sbank_tjn,srek_tjn,stt_up,stt_val,nmrekk){                
        var nox = parts = nomor.split("/");
        var nox1 = nox[0]; var nox2 = nox[1]; var nox3 = nox[2]; var nox4 = nox[3]; var nox5 = nox[4]; var nox6 = nox[5];  var nox7 = nox[6];
        var hasil = "/"+nox2+"/"+nox3+"/"+nox4+"/"+nox5+"/"+nox6;
                
        $("#nomor").attr("value",nox1);
        $("#nomor_tambahan").attr("value",hasil);
        $("#nomor_hide").attr("value",nomor);
        $("#nomor_hide_urut").attr("value",nox1);        
        $("#tanggal").datebox("setValue",tgl);
		$("#pengirim").combogrid("setValue",jenis_status);                
        
        //$("#bidang").combogrid("setValue",bidang);        
		/* if (jenis_status==1){            
            $("#jns_tetap").attr("checked",true);
        } else {
            $("#jns_tetap").attr("checked",false);
        } */
        $("#rekening_awal").combogrid("setValue",rekwal);        
        $("#nm_rekening_tujuan").attr("Value",snmrek_awal);
        $("#kd_bank_tujuan").combogrid("setValue",sbank_tjn);        
        $("#rekening_tujuan").combogrid("setValue",srek_tjn);
                
        $("#nmrek_sts").attr("value",nmrekk.toUpperCase());
        $("#ket").attr("value",lcket)
        $("#jns_sts").attr("value",sbank)
        $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1_pend/4/'+kode});         
        $("#giat").combogrid("setValue",lckdgiat);
        //$('#rek_sts').combogrid('setValue',nox5);        
        $("#jumlahtotal").attr("value",lctotal);
        
       // alert(status_kasda);
        
        if(stt_up==0){
            $('#xsimpan').linkbutton('enable');
             $('#xhapus').linkbutton('enable');
        }else{
             $('#xsimpan').linkbutton('disable');
             $('#xhapus').linkbutton('disable');
        }
        
    }
    
    function get_edt(lcrekedt,lcnmrekedt,lcnilaiedt){
        $("#rek_edt").attr("value",lcrekedt);
        $("#nmrek_edt").attr("value",lcnmrekedt);
        $("#nilai_edt").attr("value",lcnilaiedt);
        $("#nilai_edth").attr("value",lcnilaiedt);
        $("#dialog-modal_edit").dialog('open');
    } 
    
    
    function kosong(){
        get_urut();        
        lcstatus = 'tambah';
        //$("#nmrek_sts").attr("value",'');
        $("#nomor_tambahan").attr("value",'');
        $("#no_kas").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $('#noterima').combogrid('setValue','');
        //$('#rek_sts').combogrid('setValue','');
        $("#tanggal").datebox("setValue",'');
        $("#tanggalkas").datebox("setValue",'');
        $("#rekening_awal").combogrid("setValue",'');        
        $("#nm_rekening_tujuan").attr("Value",'');
        $("#kd_bank_tujuan").combogrid("setValue",'');        
        $("#rekening_tujuan").combogrid("setValue",'');        
        $("#pengirim").combogrid("setValue",'');
        $("#ket").attr("value",'');
        $("#nmgiat").attr("value",'');
        //$("#nmrek_sts").attr("value",'');
        $("#nmpengirim").attr("value",'');
        $("#jumlahtotal").attr("value",0);
        var kode = '';
        var nomor = '';
        $('#giat').combogrid('setValue','');
        
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd_cms/load_sts',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    function cari_tgl(){
    
    var kriteria = $('#tglvoucher').datebox('getValue'); 
    if(kriteria==''){alert('Tanggal Tidak Boleh Kosong'); exit();}else{  
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd_cms/load_sts_tgl',
        queryParams:({cari:kriteria})
        });        
     });
    }
    }
    
    function cetak_list(){
    
    var kriteria = $('#tglvoucher').datebox('getValue'); 
    if(kriteria==''){alert('Tanggal Tidak Boleh Kosong'); exit();}else{  
        var url = '<?php echo site_url(); ?>/tukd_cms/cetak_sts_cms';
        window.open(url+'/'+kriteria+'/LIST STS '+kriteria, '_blank');
        window.focus();  
    }
    }
    
    function append_save(){        
        
            var ckdrek = $('#cmb_rek').combogrid('getValue');
            var lcno = document.getElementById('nomor').value;
            var lcnm = document.getElementById('nmrek').value;
            var lcnl = angka(document.getElementById('nilai').value);
            var lstotal = angka(document.getElementById('jumlahtotal').value);
            var lcnl1 = number_format(lcnl,2,'.',',');
                                     
            if (ckdrek != '' && lcnl != 0 ) {
                total = number_format(lstotal+lcnl,2,'.',',');
                cid = cid + 1;            
                $('#dg1').datagrid('appendRow',{id:cid,no_sts:lcno,kd_rek5:ckdrek,rupiah:lcnl1,nm_rek:lcnm});    
                $('#jumlahtotal').attr('value',total);    
                rek_filter(); 
            }
             
            $('#cmb_rek').combogrid('setValue','');
            $('#nilai').attr('value','0');
            $('#nmrek').attr('value','');
        
    }     
    
    function hapus_detail(){
        var lstotal = angka(document.getElementById('jumlahtotal').value);
        var rows       = $('#dg1').edatagrid('getSelected');
        
        bno_terima    = rows.no_terima;
        bnilai        = rows.nilai;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, No Terima :  '+bno_terima+' - Nilai :  '+bnilai+' ?');
        
		angka_nilai=angka(bnilai);
		
        if ( tny == true ) {
            alert('Silahkan Simpan Kembali');
			hasil = number_format(lstotal-angka_nilai,2,'.',',');            
			$('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
            $('#jumlahtotal').attr('value',hasil);              
        }
			
    }
	
     function rek_filter(){
       
        var crek='';
         $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var i=0;i<rows.length;i++){
				crek   = crek+"A"+rows[i].kd_rek5+"A";
                if (i<rows.length && i!=rows.length-1){
                    crek = crek+'B';
                }
            }
               $('#dg1').datagrid('unselectAll');
          $('#cmb_rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_ag2/'+kode+'/'+giat+'/'+crek});  
    }
    
    
    function rek_fil(){
       
        var crek='';
         $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var i=0;i<rows.length;i++){
				crek   = crek+"A"+rows[i].kd_rek5+"A";
                if (i<rows.length && i!=rows.length-1){
                    crek = crek+'B';
                }
            }
               $('#dg1').datagrid('unselectAll');
          $('#cmb_rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek1/'+crek});  
    }
    
    //data grid Detail STS
    function set_grid(){
        $('#dg1').edatagrid({  
            columns:[[
                {field:'id',
        		title:'ID',    		
                hidden:"true"},
                {field:'no_sts',
        		title:'No STS',    		
                hidden:"true"},
				{field:'no_terima',
        		title:'Nomor Terima',
                width:4},
        	    {field:'kd_rek5',
        		title:'Nomor Rekening',
                width:2},               
                {field:'nilai',
        		title:'Rupiah',
                align:'right',
                width:2},
				{field:'hapus',title:'',width:1,align:"center",
                    formatter:function(value,rec){ 
						return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                },
                {field:'kd_rek6',
        		title:'Nomor Rekening',
                width:1,hidden:true}
                
            ]]
        });    
    }    
    
    function tambah(){
        var lcno = document.getElementById('nomor').value;
        var cjnstetap = document.getElementById('jns_tetap').checked;
         var giat  = '1';
        if(cjnstetap==true){
            $("#dialog-modal_t").dialog('open');
        } else {

            if(lcno !=''){
            $("#dialog-modal").dialog('open');
            $('#nilai').attr('value','0');
            $('#nmrek').attr('value','');
            var kode = document.getElementById('skpd').value;
            var giat = $('#giat').combogrid('getValue');
            } else {
                alert('Nomor Sts Tidak Boleh kosong')
                document.getElementById('no_kas').focus();
                exit();
            }
            
            if(giat !=''){
               rek_filter(); 
            }else{
               rek_fil();
            }
            
        }
                
    }
    
    function cetak(){
        $("#dialog-modal_cetak").dialog('open');     
        var kode = document.getElementById('nomor_hide').value;
        $('#cmb_sts').combogrid('setValue',kode);        
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $("#dialog-modal_t").dialog('close');
        $("#dialog-modal_cetak").dialog('close');
        $("#dialog-modal_edit").dialog('close');
    }    
    
    
    function hapus_rek(){
        var lckurang = angka(lnnilai);
        var lstotal  = angka(document.getElementById('jumlahtotal').value);
        lntotal      =  number_format(lstotal - lckurang,2,'.',',');
        
        $("#jumlahtotal").attr("value",lntotal);
        $('#dg1').datagrid('deleteRow',idx);     
    }
    
    function hapus(){
        var cnomor = document.getElementById('nomor').value;
        var cnomor_tam = document.getElementById('nomor_tambahan').value;
        var cnomor_sts = cnomor+cnomor_tam; 
        
        var nox = parts = cnomor_tam.split("/");
        var nox1 = nox[0]; var nox2 = nox[1]; var nox3 = nox[2]; var nox4 = nox[3]; var nox5 = nox[4]; var nox6 = nox[5];  var nox7 = nox[6];
        
        if(nox5==''){
            alert("Rekening Belum Di Pilih ");
            exit();
        }
        
        if(nox6==''){
            alert("Tanggal Belum Di Pilih");
            exit();
        }       
        
        var urll   = '<?php echo base_url(); ?>index.php/tukd_cms/hapus_sts';
		var del=confirm('Anda yakin akan menghapus Nomor Penyetoran '+cnomor_sts+'  ?');
		if  (del==true){
			$(document).ready(function(){
			 $.post(urll,({no:cnomor_sts}),function(data){
				status = data;
				if (status=='0'){
					alert('Gagal Hapus..!!');
					exit();
				} else {
					alert('Data Berhasil Dihapus..!!');
                                        
					exit();
				}
			 });
			});  
		}
    }
    
    
    
    function simpan_sts(){
       
        var cbankk    = document.getElementById('jns_sts').value;
        //var ctglkas   = $('#tanggalkas').datebox('getValue');       
        var cno       = document.getElementById('nomor').value;        
        var cno_tamb       = document.getElementById('nomor_tambahan').value;        
        var cno_hide  = document.getElementById('nomor_hide').value;   
        var cno_hide_urut  = document.getElementById('nomor_hide_urut').value;     
		var no_terima = document.getElementById('noterima').value;        
        var cbank     = '';//$('#bank').combogrid('getValue'); 
        var ctgl      = $('#tanggal').datebox('getValue');       
        var cskpd     = document.getElementById('skpd').value;
        var cpengirim = $('#pengirim').combogrid('getValue');
        var cnmskpd   = document.getElementById('nmskpd').value;
        var lcket     = document.getElementById('ket').value;
        var cjnsrek   = '4';//$('#jns_trans').combobox('getValue');
        var cgiat     = $('#giat').combogrid('getValue');
        //var rekening2 = $('#rek_sts').combogrid('getValue');
        var lcrekbank = '';//document.getElementById('rek_bank').value;
        var lntotal   = angka(document.getElementById('jumlahtotal').value);
        var cstatus   = document.getElementById('jns_tetap').checked;
       
        var xno_sts = cno+cno_tamb;  
        
        var nox = parts = cno_tamb.split("/");
        var nox1 = nox[0]; var nox2 = nox[1]; var nox3 = nox[2]; var nox4 = nox[3]; var nox5 = nox[4]; var nox6 = nox[5];  var nox7 = nox[6];
        
        var ket_cms = cno+'/'+nox3+'/'+nox4.substring(0,7)+'/'+nox5+'/'+nox6;
        
        if(nox5==''){
            alert("Rekening Belum Di Pilih ");
            exit();
        }
        
        if(nox6==''){
            alert("Tanggal Belum Di Pilih");
            exit();
        }        
        
        kdrek = kdrek.split(' ').join('');
        //rekening2 = rekening2.split(' ').join('');
        
		var tahun_input = ctgl.substring(0, 4);
		
		if(cpengirim==''){
			cpengirim='0';
		}
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}        
        if (cstatus==false){
           cstatus=0;
        }else{
            cstatus=1;
        }
        
        if (ctgl==''){
            alert('Tanggal STS Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        
        var crekawal = $('#rekening_awal').combogrid('getValue');
        var cnmrekawal = document.getElementById('nm_rekening_tujuan').value;
        var crektujuan = $("#rekening_tujuan").combogrid("getValue"); 
        var cbanktujuan = $('#kd_bank_tujuan').combogrid('getValue');        
        
        if (crekawal == '' || cnmrekawal == '' || crektujuan == '' || cbanktujuan == ''){
			alert('Isian Rekening Belum Lengkap!');
			exit();
		}
        
        if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhkasin_pkd_cms',field:'urut'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
						//mulai
            $('#dg1').datagrid('selectAll');
            var rows  = $('#dg1').datagrid('getSelections');           
    		lcval_det = '';
            for(var i=0;i<rows.length;i++){
				cnoterima  = rows[i].no_terima;
                ckdrek  = rows[i].kd_rek5;
                ckdrek6  = rows[i].kd_rek6;              
                cnilai  = angka(rows[i].nilai);  
                if(i>0){
    				lcval_det = lcval_det+",('"+cskpd+"','"+xno_sts+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"','"+cnoterima+"','"+ckdrek6+"')";
    			}else{
    				lcval_det = lcval_det+"('"+cskpd+"','"+xno_sts+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"','"+cnoterima+"','"+ckdrek6+"')";
    			}              
    		}
            $('#dg1').datagrid('unselectAll'); 
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd_cms/simpan_sts_pendapatan',
                    data: ({tabel:'trhkasin_pkd_cms',cid:'no_sts',lcid:xno_sts,no:xno_sts,bank:cbank,tgl:ctgl,skpd:cskpd,pengirim:cpengirim,ket:lcket,jnsrek:cjnsrek,giat:cgiat,rekbank:lcrekbank,total:lntotal,value_det:lcval_det,sts:cstatus,no_terima:no_terima,surut:cno,bankk:cbankk,rek_awal:crekawal,anrek_awal:cnmrekawal,rek_tjn:crektujuan,rek_bnk:cbanktujuan,ketcms:ket_cms}),
                    dataType:"json",
                    success:function(data){
                        status = data ;
                        if (status=='0'){
                             alert('gagal');
                             exit();
                        }  else  
                        if (status=='2'){
                             alert("Data Tersimpan...!!!");
                             $("#nomor_hide").attr("Value",xno_sts);  
                             $("#nomor_hide_urut").attr("Value",cno); 
                             lcstatus = 'edit'; 
							section1();
							//refresh();
                        }
                    }
                });
            });
		//akhir-mulai 
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
                    data: ({no:cno,tabel:'trhkasin_pkd_cms',field:'urut'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=cno_hide_urut){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide_urut){
						alert("Nomor Bisa dipakai");
			//mulai			
            
             $('#dg1').datagrid('selectAll');
             var rows  = $('#dg1').datagrid('getSelections');           
    		lcval_det = '';
            for(var i=0;i<rows.length;i++){
				cnoterima  = rows[i].no_terima;
                ckdrek  = rows[i].kd_rek5;
                ckdrek6  = rows[i].kd_rek6;              
                cnilai  = angka(rows[i].nilai);  
                if(i>0){
    				lcval_det = lcval_det+",('"+cskpd+"','"+xno_sts+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"','"+cnoterima+"','"+ckdrek6+"')";
    			}else{
    				lcval_det = lcval_det+"('"+cskpd+"','"+xno_sts+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"','"+cnoterima+"','"+ckdrek6+"')";
    			}              
    		}
            $('#dg1').datagrid('unselectAll');    
              $(document).ready(function(){
                    $.ajax({
                        type     : "POST",
                        url      : '<?php echo base_url(); ?>/index.php/tukd_cms/update_sts_pendapatan_ag',
						data: ({tabel:'trhkasin_pkd_cms',cid:'no_sts',lcid:xno_sts,no:xno_sts,bank:cbank,tgl:ctgl,skpd:cskpd,pengirim:cpengirim,ket:lcket,jnsrek:cjnsrek,giat:cgiat,rekbank:lcrekbank,total:lntotal,value_det:lcval_det,sts:cstatus,no_terima:no_terima,nohide:cno_hide,surut:cno,bankk:cbankk,rek_awal:crekawal,anrek_awal:cnmrekawal,rek_tjn:crektujuan,rek_bnk:cbanktujuan,ketcms:ket_cms}),
						dataType : "json",
                        success  : function(data){
                            
                            status = data;
                            if (status=='0'){
                                 alert('gagal');
                                 exit();
                            } else 
                            if (status=='2'){
                                alert("Data Tersimpan...!!!");  
                                $("#nomor_hide").attr("Value",xno_sts);
                                lcstatus = 'edit'; 
								section1();
								 //refresh();                                 
                            }
                            
                        }
                    });
                });
        //akhir
        }
			}
		});
		});
        }
       
    }    
    
    function jumlah(){

        var lcno = document.getElementById('nomor').value;
        var lcno_tam = document.getElementById('nomor_tambahan').value;
        
        var lctno_sts = lcno + '' + lcno_tam; 
        var lcnm = document.getElementById('nmrek1').value;
        ckdrek = $('#rek').combogrid('getValue'); 
        var rows = $('#dg_tetap').datagrid('getChecked');
        cid = cid + 1;      
        
        var lstotal = angka(document.getElementById('jumlahtotal').value);
        
        
        var lnjm = 0;    
        	for(var i=0;i<rows.length;i++){
        	   ltmb = angka(rows[i].nilai);
               lnjm = lnjm + ltmb;
        	   }
  
            total = number_format(lstotal+lnjm,2,'.',',');
            $('#jumlahtotal').attr('value',total);    
            lcjm = number_format(lnjm,2,'.',',')               

            $('#dg1').datagrid('appendRow',{id:cid,no_sts:lctno_sts,kd_rek5:ckdrek,rupiah:lcjm,nm_rek:lcnm});
             
          keluar();
    }
  
  
    function delCommas(nStr)
    {
        var no =nStr.split(",").join("");
        return no1 = eval(no);
    }
    
    function edit_detail(){
    
         var lnnilai = angka(document.getElementById('nilai_edt').value);
         var lnnilai_sb = angka(document.getElementById('nilai_edth').value);
         var lstotal = angka(document.getElementById('jumlahtotal').value);
         
         lcnilai = number_format(lnnilai,2,'.',',');
         total = lstotal - lnnilai_sb + lnnilai; 
         ftotal = number_format(total,2,'.',',');
         $('#dg1').datagrid('updateRow',{
            	index: idx,
            	row: {
            		rupiah: lcnilai                    
            	}
         });
         $('#jumlahtotal').attr('value',ftotal);  
         keluar();
    }
	function input_nomor(){
		var no_awal = document.getElementById('no_kas').value;
		$("#nomor").attr("value",no_awal);
	}
    function refresh(){
  		urll ='<?php echo base_url(); ?>index.php/tukd/sts_cms';
        window.open(urll, '_self');
        window.focus();
	   }
                  
       
    </script>

</head>
<body>


<div id="content"> 
<div id="accordion">
<h3><a href="#" id="section1">LIST - STS NON TUNAI</a></h3>
    
    <div>
    <p align="right">         
        Tanggal
        <input name="tglvoucher" type="text" id="tglvoucher" style="width:100px; border: 0;"/>            
        &nbsp;
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari_tgl();">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_list();">Cetak List</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>               
        <!--<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak</a>-->
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="List STS" style="width:870px;height:450px;" >  
        </table>
    </p> 
     <p>*) Warna Hijau = Sudah di Upload, Warna Biru = Sudah di Validasi Kasda</p>
    </div>   

<h3><a href="#" id="section2" onclick="javascript:set_grid();">Input Surat Tanda Setoran</a></h3>

   <div  style="height: 350px;">

   <p>       
        <table align="center" style="width:100%;" border="0">
            <!--<tr>
                <td>No. KAS</td>
                <td><input type="text" id="no_kas" onkeyup="javascript:input_nomor();" style="width: 200px;"/><input type="hidden" id="nomor_hide" style="width: 200px;"/></td>
                <td>Tanggal KAS</td>
                <td><input type="text" id="tanggalkas" style="width: 140px;" /></td>     
            </tr>-->            
            
            <tr>
                <td>No.</td>
                <td colspan="2"><input type="text" id="nomor" style="width: 35px;" readonly="true"/>
                <input type="hidden" id="nomor_hide" style="width: 200px;"/>
                <input type="hidden" id="nomor_hide_urut" style="width: 200px;"/>
                <input type="text" name="nomor_tambahan" id="nomor_tambahan" style="width: 220px;" readonly="true"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Tanggal <input type="text" id="tanggal" style="width: 140px;" />
                </td>
                
                    
            </tr>            
            <!--<tr>
                <td>BANK</td>
                <td><input type="text" id="bank" name="bank" style="border:0;width: 150px;" readonly="true" /></td> 
                <td>Rekening Bank</td>
                <td><input type="text" id="rek_bank" style="width: 300px;"/></td>
            </tr>-->
            <tr>
                <td>S K P D</td>
                <td><input id="skpd" name="skpd" style="width: 137px;" /></td>
                <td colspan="2" align="left"><input type="text" id="nmskpd" style="border:0;width: 450px;" readonly="true"/></td>
                                
            </tr>
            <!--<tr>
                <td>BIDANG</td>
                <td><input id="bidang" name="bidang"  style="width: 140px; border:0;" readonly="true" /></td>
                <td><input type="text" id="nmbidang" style="border:0;width: 350px;" readonly="true"/></td>                            
            </tr>-->            
			<tr>
                <td>Pengirim</td>
                <td><input id="pengirim" name="pengirim" style="width: 140px;" /></td>
                <td colspan="2" align="left"><input type="text" id="nmpengirim" style="border:0;width: 450px;" readonly="true"/></td>                                
            </tr>
            <tr>
                <td colspan="5">
                <table width="100%">
                    <tr bgcolor="#E6E6FA">
                        <td>Rek. Penerimaan</td>
                        <td><input type="text" id="rekening_awal" style="border:0;width: 150px;" readonly="true"/></td>
                        <td>Nama Bank</td>
                        <td><input type="text" id="kd_bank_tujuan" style="border:0;width: 200px;" onkeyup="javascript:cek_huruf(this);"/></td>
                    </tr>
                    <tr bgcolor="#FFE4E1">
                        <td>Rek. Tujuan</td>
                        <td><input type="text" id="rekening_tujuan" style="border:0;width: 150px;" /></td>
                        <td>Nama Rek.</td>
                        <td><input type="text" id="nm_rekening_tujuan" style="border:0;width: 200px;"/></td>
                    </tr>
                    
                </table>                
                </td>
            </tr> 
            <tr>
                <td>Uraian</td>
                <td colspan="3"><textarea name="ket" id="ket" cols="80" rows="1" style="border: 0;"  ></textarea></td>                
            </tr>            
            <!--<tr>
                <td>Jenis Transaksi</td>
                <td colspan="3">
                <input  id="jns_trans" name="jns_trans" style="border:0;width: 150px;"/>                 
                </td> 
            </tr>-->
            <tr>
            <td>Kegiatan</td>
            <td><input id="giat" name="giat" style="width: 200px;" /></td>
            <td colspan="2"><input type="text" id="nmgiat" style="border:0;width: 450px;" readonly="true"/></td></tr>
            <!--<tr>
                <td>Rekening</td>
                <td><input id="rek_sts" name="rek_sts" style="width: 200px;" /></td>
                <td><input type="text" id="nmrek_sts" style="border:0;width: 450px;" readonly="true"/></td>                
            </tr>--> 
            <tr>
           <!-- <td colspan="4">Dengan Penetapan --><input id="jns_tetap" hidden type="checkbox"/></td>            
            </tr>
			<!--<tr>
            <td>No Penerimaan</td>
            <td colspan="2"><input type="text" id="no_terima" style="border:0;width: 200px;" readonly="true"/></td></tr>-->
			<tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
				<td width='20%'>No Terima</td>
			    <td width="80%"><input id="noterima" name="noterima" style="width:200px" /></td> 
			</tr> 
            <tr>
                <td>Jenis Setoran</td>
                <td>
                <select id="jns_sts" name="jns_sts" onclick="javascript:runjns();">
                <!--<option value="TN">Tunai</option>-->
                <option value="BNK">Bank</option>
                </select>               
                 </td>                
            </tr> 			
            <tr>
                <td colspan="4" align="right">
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Baru</a>               
                <a class="easyui-linkbutton" id="xsimpan" iconCls="icon-save" plain="true" onclick="javascript:simpan_sts();">Simpan</a>
                <a class="easyui-linkbutton" id="xhapus" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak STS</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a></td>

            </tr>
        </table>          
        <table id="dg1" title="Detail STS" style="width:870px;height:350px;" >  
        </table>  
        <!--<div id="toolbar">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Rekening</a>
    		<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rek();">Hapus Rekening</a>    		
        </div>-->
        
                
   </p>
   <table border="0" align="right" style="width:100%;"><tr>
   <td style="width:75%;" align="right"><B>JUMLAH</B></td>
   <td align="right"><input type="text" id="jumlahtotal" readonly="true" style="border:0;width:200px;text-align:right;"/></td>
   </tr>
   </table>
   
   </div>
</div>
</div>


<div id="dialog-modal" title="Input Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">Kode Rekening:</td>
            <td><input id="cmb_rek" name="cmb_rek" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td width="110px">Nama Rekening:</td>
            <td><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr> 
           <td width="110px">Nilai:</td>
           <td><input type="text" id="nilai" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
        </tr>
    </table>  
    </fieldset>
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>

<div id="dialog-modal_edit" title="Edit Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">Kode Rekening:</td>
            <td><input type="text" id="rek_edt" readonly="true" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td width="110px">Nama Rekening:</td>
            <td><input type="text" id="nmrek_edt" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr> 
           <td width="110px">Nilai:</td>
           <td><input type="text" id="nilai_edt" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/>
               <input type="hidden" id="nilai_edth"/> 
           </td>
        </tr>
    </table>  
    </fieldset>
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:edit_detail();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>


<div id="dialog-modal_cetak" title="Cetak STS">
    
    <fieldset>
    <table>
        <tr>
            <td width="110px">No STS:</td>
            <td><input id="cmb_sts" name="cmb_sts" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td width="110px">PA/KPA:</td>
            <td><input id="ttd1" name="ttd1" style="width: 200px;" /></td>
        </tr>
		<tr>
            <td width="110px">BP/BPP:</td>
            <td><input id="ttd2" name="ttd2" style="width: 200px;" /></td>
        </tr>
    </table>  
    </fieldset>
     <fieldset>
    <table border="0">
        <tr align="center">
            <td></td>
            <td width="100%" align="center"><a  href="<?php echo site_url(); ?>/tukd_cms/cetak_stss_cms" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  </td>
        </tr>
    </table>  
    </fieldset>
    
	
</div>


<div id="dialog-modal_t" title="Checkbox Select">
<table border="0">
<tr>
<td>Rekening</td>
<td><input id="rek" name="rek" style="width: 140px;" />  <input type="text" id="nmrek1" style="border:0;width: 400px;" readonly="true"/></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr><td colspan="2">
    <table id="dg_tetap" style="width:770px;height:350px;" >  
        </table>
    </td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr><td colspan="2" align="center">
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:jumlah();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a></td>
</tr>
</table>  
</div>  	
</body>
</html>