<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Temperature</th>
 		<th>Type</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->temperature)) {{$lv->details->temperature}} @endif 
		 			 @if(!empty($lv->details->unit)) {{$lv->details->unit}} @endif
		 		</td>
		 		<td> @if(!empty($lv->details->type)) {{$lv->details->type}} @endif 
		 		</td>
		 		
		 		<td> @if(!empty($lv->details->temperatureFlag)) {{$lv->details->temperatureFlag}} @endif</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>