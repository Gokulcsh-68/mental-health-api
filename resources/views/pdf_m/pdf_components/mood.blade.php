<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Type</th>
 	</tr>
 	</thead>
	@foreach($lists as $lk => $lv)	
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->act_date)) {{date('d-M-Y',strtotime($lv->act_date))}} @endif</td>
		 		<td> @if(!empty($lv->act_time)) {{$lv->act_time}} @endif</td>
		 		<td> @if(!empty($lv->act_type)) {{$lv->act_type}} @endif</td>
		 	</tr>
		 	</tbody>	    		
    @endforeach
 </table>