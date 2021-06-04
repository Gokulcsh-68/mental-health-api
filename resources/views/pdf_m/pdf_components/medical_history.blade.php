<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Condition</th>
 		<th>Onset Date</th>
 		<th>Recovered Date</th>
 		<th>Treated by</th>
 		<th>Treatment</th>
 		<th>Remarks</th>
 		<th>Status</th>
 	</tr>
</thead>
	@foreach($lists as $lk => $lv)		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->condition->name)) {{$lv->values->condition->name}} @endif</td>

		 		<td> @if(!empty($lv->values->onset_date)) {{date('d-M-Y',strtotime($lv->values->onset_date))}} @endif</td>

		 		<td> @if(!empty($lv->values->recovered_on)) {{date('d-M-Y',strtotime($lv->values->recovered_on))}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->treated_by)) {{$lv->values->treated_by}} @endif</td>

		 		<td> @if(!empty($lv->values->treatment)) {{$lv->values->treatment}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->remarks)) {{$lv->values->remarks}} @endif</td>

		 		<td> @if(!empty($lv->values->is_active)) @if($lv->values->is_active) Active @else Inactive @endif @endif</td>
		 	</tr>
		 	</tbody>    		
    @endforeach
 </table>