<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>SPO2</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->spo2)) {{$lv->details->spo2}} @endif 
		 			 @if(!empty($lv->details->unit)) {{$lv->details->unit}} @endif
		 		</td>		 		
		 		<td> @if(!empty($lv->details->spo2Flag)) {{$lv->details->spo2Flag}} @endif</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>