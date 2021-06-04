<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Signs / Symptoms</th>
 		<th>Location</th>
 		<th>Quality / Type</th>
 		<th>Severity</th>
 		<th>Duration & Timing</th>
 		<th>Date</th>
 		<th>Context</th>
 		<th>Relieved</th>
 		<th>Worsened</th>
 		<th>Free Text</th>
 	</tr>
</thead>
	@foreach($lists as $lk => $lv)		    		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->signs)) {{$lv->values->signs}} @endif</td>
		 		<td> @if(!empty($lv->values->location)) {{$lv->values->location}} @endif</td>
		 		<td> @if(!empty($lv->values->quality)) {{$lv->values->quality}} @endif </td>
		 		<td> @if(!empty($lv->values->severity)) {{$lv->values->severity}} @endif</td>
		 		<td> @if(!empty($lv->values->duration)) {{$lv->values->duration}} @endif &
		 		 @if(!empty($lv->values->timing)) {{$lv->values->timing}} @endif</td>
		 		<td> @if(!empty($lv->values->date)) {{date('d-M-Y',strtotime($lv->values->date))}} @endif</td>
		 		<td> @if(!empty($lv->values->context)) {{$lv->values->context}} @endif</td>
		 		<td> @if(!empty($lv->values->relieved)) {{$lv->values->relieved}} @endif</td>
		 		<td> @if(!empty($lv->values->worsened)) {{$lv->values->worsened}} @endif</td>
		 		<td> @if(!empty($lv->values->free_text)) {{$lv->values->free_text}} @endif</td>
		 	</tr>

		 	</tbody>
    @endforeach
 </table>