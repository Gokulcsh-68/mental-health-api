<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Blood Sugar</th>
 		<th>Type</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->blood_sugar)) {{$lv->details->blood_sugar}} @endif 
		 			 @if(!empty($lv->details->unit)) {{$lv->details->unit}} @endif
		 		</td>
		 		<td> @if(!empty($lv->details->type)) {{$lv->details->type}} @endif 
		 		</td>
		 		
		 		<td> @if(!empty($lv->details->bsFlag)) {{$lv->details->bsFlag}} @endif</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>