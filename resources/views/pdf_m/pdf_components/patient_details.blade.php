
<h3 class="color_primary">Patient Details:</h3>

@foreach($patient_details as $k => $v)

	 <table>
	 	<tr>
	 		<th>Patient Name</th>
	 		<td colspan="3">{{$v->first_name}} {{$v->last_name}}</td>
	 	</tr>
	 	<tr>
	 		<th>Gender</th>
	 		<td>{{$v->gender}}</td>
	 		<th>Date of Birth</th>
	 		<td>{{date('d-M-Y',strtotime($v->dob))}}</td>
	 	</tr>
	 	<tr>
	 		<th>Email </th>
	 		<td>{{$v->email}}</td>
	 		<th>Contact no.</th>
	 		<td>{{$v->isd_code}} {{$v->mobile}}</td>
	 	</tr>
	 	<tr>
	 		<th>Address</th>
	 		<td colspan="3">
	 			@if(!empty($v->address->address1)) {{$v->address->address1}} @endif
	 			@if(!empty($v->address->address2)) {{$v->address->address2}}, @endif
	 			@if(!empty($v->address->city)) {{$v->address->city}} - @endif
	 			@if(!empty($v->address->zipcode)) {{$v->address->zipcode}}, @endif
	 			@if(!empty($v->address->state)) {{$v->address->state}}, @endif
	 			@if(!empty($v->address->country)) {{$v->address->country}} @endif
	 			</td>
	 	</tr>
	 </table>

@endforeach