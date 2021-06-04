<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Medicine</th>
 		<th>Type</th>
 		<th>Strength</th>
 		<th>InTake Days</th>
 		<th>Quantity</th>
 		<th>Sig Info</th>
 		<th>Start</th>
 		<th>End</th>
 		<th>Reason</th>
 		<th>Status</th>
 	</tr>
</thead>
	@foreach($lists as $lk => $lv)		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->name->name)) {{$lv->values->name->name}} @endif</td>

		 		<td> @if(!empty($lv->values->type)) {{$lv->values->type}} @endif</td>

		 		<td> @if(!empty($lv->values->strength)) {{$lv->values->strength}} @endif 
		 		@if(!empty($lv->values->strength_measurement)) {{$lv->values->strength_measurement}} @endif</td>

		 		<td> @if(!empty($lv->values->intake_total_days)) {{$lv->values->intake_total_days}} @endif</td>

		 		<td> @if(!empty($lv->values->quantity)) {{$lv->values->quantity}} @endif</td>

		 		<td> @if(!empty($lv->values->sig)) {{$lv->values->sig}} @endif</td>

		 		<td> @if(!empty($lv->values->start_date)) {{date('d-M-Y',strtotime($lv->values->start_date))}} @endif</td>

		 		<td> @if(!empty($lv->values->end_date)) {{date('d-M-Y',strtotime($lv->values->end_date))}} @endif</td>

		 		<td> @if(!empty($lv->values->reason)) {{$lv->values->reason}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->is_active)) @if($lv->values->is_active) Active @else Inactive @endif @endif</td>
		 	</tr>
		 	</tbody>    		
    @endforeach
 </table>