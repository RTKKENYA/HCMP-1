<style>
 	.input-small{
 		width: 100px !important;
 	}
  .filter-row{
    margin: none;
  }
 </style>
<div class="filter">
<select id="commodity_filter" class="form-control col-md-2">
	<option value="0">Select Commodity</option>
	<?php
	foreach($c_data as $data):
			$commodity_name = $data['commodity_name'];	
			$commodity_id = $data['commodity_id'];
			echo "<option value='$commodity_id'>$commodity_name</option>";
	endforeach;
	?>
</select>
<input type="text" name="from"  id="from" 
placeholder="FROM" class="form-control input-small col-md-1 clone_datepicker_normal_limit_today" />
<input type="text" name="to"  id="to" placeholder="TO" class="form-control input-small col-md-1 clone_datepicker_normal_limit_today" />		
<!--<select name="year" id="year_filter" style="width: 7.8em;">
		<option value="0">Select Year</option>
		<option value="2014">2014</option>
		<option value="2013">2013</option>
</select>-->
<select id="plot_value_filter" class="form-control col-md-3">
	<option value="0">Select Plot value</option>
	<option value="packs">Packs</option>
	<option value="units">Units</option>
	<option value="ksh">KSH</option>
	<option value="service_point">Per Service Point</option>
</select> 
<button class="btn btn-sm btn-success" id="filter" name="filter"><span class="glyphicon glyphicon-filter">Filter</button> 
	
</div>

<div class='graph-section' id='graph-section'></div>

<script>
	$(document).ready(function() 
	{
		json_obj = { "url" : "assets/img/calendar.gif'",};
		var baseUrl=json_obj.url;
		<?php echo @$graph_data; ?>
	
			$("#filter").button().click(function(e) 
			{
				e.preventDefault();
				var from =$("#from").val();
		        var to =$("#to").val();
		        
		        if(from==''){from="NULL";}
		        if(to==''){to="NULL";}
				var url = "reports/filtered_consumption/"+
				        $("#commodity_filter").val()+
				        "/"+$("#year_filter").val()+
				         "/"+$("#plot_value_filter").val();
 				ajax_request_replace_div_content(url,'.graph-section');
		
          });
          
	  //	-- Datepicker	limit today		
	$(".clone_datepicker_normal_limit_today").datepicker({
    maxDate: new Date(),				
	dateFormat: 'd M yy', 
	changeMonth: true,
	changeYear: true,
	buttonImage: baseUrl,       });	
		
  });
</script>