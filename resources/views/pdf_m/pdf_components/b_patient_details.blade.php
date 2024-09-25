

@foreach($b_patient_details as $k => $v)

	 <table>
	 	<tr>
	 		<td><b>MRN:</b> @if(!empty($v->additional_info->mrn_number)) {{$v->additional_info->mrn_number}} @endif</td>
	 	</tr>
	 </table>

@endforeach