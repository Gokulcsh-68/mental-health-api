<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Hemoglobin</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->hemoglobin)) {{$lv->details->hemoglobin}} g/dL @endif 
		 			 
		 		</td>		
		 		<td> 
		 			@if(!empty($gender)) 

		 				@if(!empty($lv->details->hemoglobin))

			 				@if($gender == 'Male') 

			 				{{ $lv->details->hemoglobin >= '13.8' && $lv->details->hemoglobin <= '17.2'?'Normal':'Danger' }}

			 				@endif

			 				@if($gender == 'Female') 

			 				{{ $lv->details->hemoglobin >= '12.1' && $lv->details->hemoglobin <= '15.1' ?'Normal':'Danger' }}

			 				@endif

		 				@endif

		 		 	@endif
		 		</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>