<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Surgical</th>
 		<th>Due to</th>
 		<th>Notes</th>
 		<th>Status</th>
 		<th>Prescribed</th>
 		<th>Done by</th>
 		<th>Pre Notes</th>
 		<th>Post operative</th>
 	</tr>
</thead>
	@foreach($lists as $lk => $lv)		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->surgery_date)) {{date('d-M-Y',strtotime($lv->values->surgery_date))}} @endif</td>

		 		<td> @if(!empty($lv->values->surgical_name->name)) {{$lv->values->surgical_name->name}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->pre_surgery_diagnosis->name)) {{$lv->values->pre_surgery_diagnosis->name}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->surgery_notes)) {{$lv->values->surgery_notes}} @endif</td>

		 		<td> @if(!empty($lv->values->surgery_status)) {{$lv->values->surgery_status}} @endif
		 			
		 		 @if(!empty($lv->values->complication_status)) <br><br> <b>Complications:</b> {{$lv->values->complication_status}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->prescribed_by)) {{$lv->values->prescribed_by}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->surgery_done_by)) {{$lv->values->surgery_done_by}} @endif</td>
		 		
		 		<td> @if(!empty($lv->values->pre_surgery_notes)) {{$lv->values->pre_surgery_notes}} @endif</td>

		 		<td> @if(!empty($lv->values->post_operative_problems)) {{$lv->values->post_operative_problems}} @endif</td>
		 		
		 	</tr>
		 	</tbody>    		
    @endforeach
 </table>