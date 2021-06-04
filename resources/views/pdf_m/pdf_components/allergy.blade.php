<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Allergy</th>
 		<th>Type</th>
 		<th>Category</th>
 		<th>Reaction</th>
 		<th>Other Reaction</th>
 		<th>Severity</th>
 		<th>Onset</th>
 		<th>Treated by</th>
 		<th>Notes</th>
 		<th>Status</th>
 	</tr>
 	</thead>
	@foreach($lists as $lk => $lv)	
		<tbody>	    		
		 	<tr>
		 		<td> @if(!empty($lv->values->name->name)) {{$lv->values->name->name}} @endif</td>
		 		<td> @if(!empty($lv->values->type)) {{$lv->values->type}} @endif</td>
		 		<td> @if(!empty($lv->values->category)) {{$lv->values->category}} @endif </td>
		 		<td> @if(!empty($lv->values->reaction->name)) {{$lv->values->reaction->name}} @endif</td>
		 		<td> @if(!empty($lv->values->other_reaction)) {{$lv->values->other_reaction}} @endif</td>
		 		<td> @if(!empty($lv->values->severity)) {{$lv->values->severity}} @endif</td>
		 		<td> @if(!empty($lv->values->date)) {{date('d-M-Y',strtotime($lv->values->date))}} @endif</td>
		 		<td> @if(!empty($lv->values->treated_by)) {{$lv->values->treated_by}} @endif</td>
		 		<td> @if(!empty($lv->values->treatment)) {{$lv->values->treatment}} @endif</td>
		 		<td> @if(!empty($lv->values->is_active)) @if($lv->values->is_active) Active @else Inactive @endif @endif</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>