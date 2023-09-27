<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i>Test API 4</h2>

       </div> 
<div id="div_display_data">Data will display here</div> 
<div ><button onclick="load_data()">load data</button></div>
 </div> 
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
     

function load_data() {    
    
    
   var postData = "action=synch_masters&privatekey=668b6d556d1daef7005bd9fc39ee533e";
   
var postUrl = '<?=base_url('/api4/index')?>';
   
alert(postUrl );

   $.ajax({
        type: "POST",
        url: postUrl,
        data: postData,
        beforeSend: function(){
            $("#div_display_data").html("<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>");
        },
        success: function(data){			 
            $("#div_display_data").html(data);			 
        }
    }); 
    
}
 
    
</script>