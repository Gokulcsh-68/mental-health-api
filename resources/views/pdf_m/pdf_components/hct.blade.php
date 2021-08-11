<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>HCT</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->hct)) {{$lv->details->hct}} % @endif 
		 			 
		 		</td>		
		 		<td> 
		 			@if(!empty($gender)) 

		 				@if(!empty($lv->details->hct))

			 				@if($gender == 'Male') 

			 				{{ $lv->details->hct >= '41' && $lv->details->hct <= '50'?'Normal':'Danger' }}

			 				@endif

			 				@if($gender == 'Female') 

			 				{{ $lv->details->hct >= '36' && $lv->details->hct <= '48'?'Normal':'Danger' }}

			 				@endif

		 				@endif

		 		 	@endif
		 		</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>