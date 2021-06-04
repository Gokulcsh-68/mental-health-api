<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Respiration</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->respiration)) {{$lv->details->respiration}} per min @endif 
		 			 
		 		</td>		
		 		<td> @if(!empty($lv->details->respirationFlag)) {{$lv->details->respirationFlag}} @endif
		 		</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>