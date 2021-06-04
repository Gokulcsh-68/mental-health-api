<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Procedure</th>
 		<th>Code</th>
 		<th>Primary Provider</th>
 		<th>Secondary Provider</th>
 		<th>Notes</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)		    		
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->date)) {{date('d-M-Y',strtotime($lv->values->date))}} @endif</td>
		 		<td> @if(!empty($lv->values->name)) {{$lv->values->name}} @endif</td>
		 		<td> @if(!empty($lv->values->code)) {{$lv->values->code}} @endif</td>
		 		<td> @if(!empty($lv->values->provider_one)) {{$lv->values->provider_one}} @endif </td>
		 		<td> @if(!empty($lv->values->provider_two)) {{$lv->values->provider_two}} @endif</td>
		 		<td> @if(!empty($lv->values->notes)) {{$lv->values->notes}} @endif</td>
		 	</tr>
		 	</tbody>
    @endforeach
 </table>