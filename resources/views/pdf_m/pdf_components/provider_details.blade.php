
<h4 class="color_secondary">Provider & Consult Details:</h4>

@foreach($provider_details as $k => $v)
	 <table>
	 	<tr>
	 		<th>Doctor Name</th>
	 		<td colspan="3">{{$v['name']}}</td>
	 		<th>Gender</th>
	 		<td>{{$v['gender']}}</td>
	 	</tr>
	 	<tr>
	 		<th>Consult Speciality</th>
	 		<td>{{ucfirst($v['consult_speciality'])}}</td>
	 		<th>License no </th>
	 		<td>{{$v['license_no']}}</td>
	 		<th>Qualification </th>
	 		<td>{{$v['qualification']}}</td>
	 	</tr>
	 	<tr>
	 		<th>Email </th>
	 		<td colspan="2">{{$v['email']}}</td>
	 		<th>Contact no.</th>
	 		<td colspan="2">{{$v['phone']}}</td>
	 	</tr>	 	
	 </table>

@endforeach