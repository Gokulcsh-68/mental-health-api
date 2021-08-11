<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Uric Acid</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->uric_acid)) {{$lv->details->uric_acid}} mg/dL @endif 
		 			 
		 		</td>		
		 		<td> 
		 			@if(!empty($gender)) 

		 				@if(!empty($lv->details->uric_acid))

			 				@if($gender == 'Male') 

			 				{{ $lv->details->uric_acid >= '4.0' && $lv->details->uric_acid <= '8.5'?'Normal':'Danger' }}

			 				@endif

			 				@if($gender == 'Female') 

			 				{{ $lv->details->uric_acid >= '2.7' && $lv->details->uric_acid <= '7.3'?'Normal':'Danger' }}

			 				@endif

		 				@endif

		 		 	@endif
		 		</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>