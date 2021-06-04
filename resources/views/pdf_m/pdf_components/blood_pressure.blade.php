<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Pulse</th>
 		<th>Systolic</th>
 		<th>Diastolic</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->pulse)) {{$lv->details->pulse}} @endif 
		 		</td>		 		
		 		<td> @if(!empty($lv->details->systolic)) {{$lv->details->systolic}} @endif
		 		</td>	 		
		 		<td> @if(!empty($lv->details->diastolic)) {{$lv->details->diastolic}} @endif
		 		</td> 		
		 		<td> @if(!empty($lv->details->bpFlag)) {{$lv->details->bpFlag}} @endif
		 		</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>