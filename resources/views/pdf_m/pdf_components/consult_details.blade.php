
@foreach($consult_details as $k => $v)
	 <table>
	 	<tr>
	 		<th>Scheduled at</th>
	 		<td>{{date('d-M-y h:i:s',strtotime($v['scheduled_at']))}}</td>
	 		<th>Started at</th>
	 		<td>{{date('d-M-y h:i:s',strtotime($v['started_at']))}}</td>
	 	</tr>
	 	<tr>
	 		<th>Ended at</th>
	 		<td>{{date('d-M-y h:i:s',strtotime($v['ended_at']))}}</td>

	 		<th>Additional Notes </th>
	 		<td>{{$v['additional_info']->consult_notes}}</td>
	 	</tr>	 	
	 </table>

@endforeach