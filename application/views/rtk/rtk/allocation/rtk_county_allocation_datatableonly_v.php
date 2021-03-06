<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/tablecloth/assets/js/jquery.tablecloth.js"></script>


<script type="text/javascript">

    $(document).ready(function() {

        /* Build the DataTable with third column using our custom sort functions */

        $('#allocation_table').dataTable({
            "sDom": "T lfrtip",
             "aaSorting": [],
             "bJQueryUI": false,
              "bPaginate": false,
              "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ records",
              },
              "oTableTools": {
              "aButtons": [      
              "copy",
              "print",
              {
                "sExtends": "collection",
                "sButtonText": 'Save',
                "aButtons": ["csv", "xls", "pdf"]
              }
              ],  
              "sSwfPath": "<?php echo base_url();?>assets/datatable/media/swf/copy_csv_xls_pdf.swf"
            }
        });

        $("#allocate").button().click(function() {

            var loading = '<div id="loading"> &nbsp;&nbsp;<img src="<?php echo base_url(); ?>assets/img/ajax-loader.gif"><span style="font-size: 13px;color: #92CA8F;margin-left:100px; font-family: calibri;">Saving Allocations</span></div>';
            $('#allocation-response').html(loading);        

            var data = $('#myform').serializeArray();
            
            // $.ajax({
            //     url: '../rtk_allocation_data/',
            //     type: 'POST',
            //     data: { form_data: data },
            //     success: function(result) {
            //         console.log(result);
            //     }
            // });
           
            $.post('../rtk_allocation_data/',
                {form_data: data},     

                function(response) {  
                    alert(response);                  
                    $('#allocation-response').html(response);
                    $('#allocation-response').addClass('alert alert-success');
                   // location.reload(true);
                    $( "#loading" ).hide();
                }).fail(function(request,error) {
                    console.log(arguments);
                    alert ( " Can't do because: " + error );
                });            
            //alert(data); 

            });

        $('.navtbl li a').click(function(e) {
            var $this = $(this);
            var thistext = $(this).text();
            $('.nav li').removeClass('active');
            $this.parent().addClass('active');
            $(".dataTables_filter label input").focus();
            $('.dataTables_filter label input').val(thistext).trigger($.Event("keyup", {keyCode: 13}));

            e.preventDefault();
        });
        $("table").tablecloth({theme: "paper"});


    });
</script>

<style>
    @import "<?php echo base_url(); ?>assets/datatable/media/css/jquery.dataTables.css";
    @import "<?php echo base_url(); ?>assets/tablecloth/assets/css/tablecloth.css";

    .alerts{
        width:95%;
        height:auto;
        background: #E3E4FA;    
        padding-bottom: 2px;
        padding-left: 2px;
        margin-left:0.5em;
        -webkit-box-shadow: 0 8px 6px -6px black;
        -moz-box-shadow: 0 8px 6px -6px black;
        box-shadow: 0 8px 6px -6px black;
    }
    .user2{
        width: 40px;
    }
    #example{
        width: 100% !important;
    }    
    .nav{
        margin-bottom: 0px;
    } 
    table{
        font-size: 13px;
        text-align: center;
        width: 100%;
    }
    table:tr{
        height: 10px;
        

    }
</style> 
<div id="nav" style="margin-top:10px;width:100%"><?php include('allocation_links.php');?></div>

<div style="width:100%;font-size: 15px;background: #F8F8F8;padding: 10px 10px 10px 10px;border-bottom: solid 1px #ccc;margin-top:5%;">
    <ul class="navtbl nav nav-pills">

        <?php foreach ($districts_in_county as $value) { ?>
            <li class=""><a href="#"><?php echo $value['district']; ?></a></li>
        <?php } ?>
       <!--a class="pull-right" href="../county_allocation/<?php echo $county_id;?>" style="line-height: 20px;margin: 8px 26px 0px 0px;text-decoration: none;color: #0088cc;">View <?php echo $countyname;?>  Allocations</a--> 

    </ul>
</div>

<div style="height:411px;overflow:scroll;">
    <?php
    $attributes = array('name' => 'myform', 'id' => 'myform');
    echo form_open('rtk_management/rtk_allocation_data/' . $county_id, $attributes);
    ?>

    <table id="allocation_table" style="width:96%" border="0px ridge #ccc">
        <thead>
            <tr>           
            <th><b>County</b></th>
            <th><b>Sub-County</b></th>
            <th><b>MFL</b></th>
            <th><b>Facility Name</b></th>                   
            <th colspan="4" style="text-align:center"><b>Screening KHB</b></th>
            <th colspan="4" style="text-align:center"><b>Confirmatory Unigold</b></th>
            <th colspan="4" style="text-align:center"><b>Tie Breaker</b></th>                                                     
        </tr>

        <tr>            
            <th></th>
            <th></th>
            <th></th>            
            <th></th>    
            <th align="center">Ending Balance</th>      
            <th align="center">AMC</th>
            <th align="center">MMOS</th>
            <th align="center">Quantity to Allocate</th>
            <th align="center">Ending Balance</th>      
            <th align="center">AMC</th>
            <th align="center">MMOS</th>      
            <th align="center">Quantity to Allocate</th>
            <th align="center">Ending Balance</th>      
            <th align="center">AMC</th>
            <th align="center">MMOS</th>
            <th align="center">Quantity to Allocate</th>        
        </tr>
        </thead>
        <tbody>
      <?php
      if(count($final_dets)>0){
       foreach ($final_dets as $value) {
        //$zone = str_replace(' ', '-',$value['zone']);
        $facil = $value['code'];

        $ending_bal_s =ceil(($value['end_bal'][0]['closing_stock'])/50); 
        $ending_bal_c =ceil(($value['end_bal'][1]['closing_stock'])/30); 
        $ending_bal_t =ceil(($value['end_bal'][2]['closing_stock'])/20);


        $order_detail_id = $value['end_bal'][0]['order_id'];

        $amc_s = str_replace(',', '',$value['amcs'][0]['amc']);
        $amc_c = str_replace(',', '',$value['amcs'][1]['amc']);
        $amc_t = str_replace(',', '',$value['amcs'][2]['amc']);

        if($amc_s==''){
          $amc_s = 0;
        }

        if($amc_c==''){
          $amc_c = 0;
        }

        if($amc_t==''){
          $amc_t = 0;
        }

        $mmos_s = ceil(($amc_s * 4)/50);
        $mmos_c = ceil(($amc_c * 4)/30);
        $mmos_t = ceil(($amc_t * 4)/20);

        if($mmos_s < $ending_bal_s){
          $qty_to_alloc_s = 0;
        }else{
          $qty_to_alloc_s = $mmos_s - $ending_bal_s;
        }

        if($mmos_c < $ending_bal_c){
          $qty_to_alloc_c = 0;
        }else{
          $qty_to_alloc_c = $mmos_c - $ending_bal_c;
        }

        if($mmos_t < $ending_bal_t){
          $qty_to_alloc_t = 0;
        }else{
          $qty_to_alloc_t = $mmos_t - $ending_bal_t;
        }

        
        ?> 
        <tr>   

          <input type ='hidden' size = '5' name ='order_detail_id' value ='<?php echo $order_detail_id;?>'>              
          <td align=""><?php echo $value['county'];?></td>
          <td align=""><?php echo $value['district'];?></td>              
          <td align=""><?php echo $value['code'];?></td>
          <td align=""><?php echo $value['name'];?></td>

          <td align="center"><?php echo $ending_bal_s;?></td>     
          <td align="center"><?php echo $amc_s;?></td>     
          <td align="center"><?php echo $mmos_s;?></td>
          <td contenteditable='true' name ='allocate_screening_khb'><?php echo $qty_to_alloc_s;?></td>    

          <td align="center"><?php echo $ending_bal_c;?></td>     
          <td align="center"><?php echo $amc_c;?></td>     
          <td align="center"><?php echo $mmos_c;?></td>          
          <td><input type ='text' size = '5' name ='allocate_confirmatory' value ='<?php echo $qty_to_alloc_c;?>'></td>    

          <td align="center"><?php echo $ending_bal_t;?></td>     
          <td align="center"><?php echo $amc_t;?></td>     
          <td align="center"><?php echo $mmos_t;?></td>          
          <td><input type ='text' size = '5' name ='allocate_tiebreaker' value ='<?php echo $qty_to_alloc_t;?>'></td>             
         
          
        </tr>
        <?php }
      }else{ ?>
      <tr>There are No Facilities which did not Report</tr>
      <?php }
      ?>      

    </tbody>
    </table>
    <br />
<div id="clear">&nbsp;</div>
    <div>
<input class="pull-left" type="button" id="allocate" value="Allocate" style="background: #F8F7F7; padding: 7px;margin: 8px 0px 5px 19px;color: #0088cc;font-family: calibri;font-size: 18px;border: 1px solid #ccc;">
<div id="allocation-response"></div>
</div>
<?php echo form_close(); ?> 
</div>

<script type="text/javascript">
  $('#trend_tab').removeClass('active_tab');    
  $('#stocks_tab').removeClass('active_tab');  
  $('#allocations_tab').addClass('active_tab');
</script>