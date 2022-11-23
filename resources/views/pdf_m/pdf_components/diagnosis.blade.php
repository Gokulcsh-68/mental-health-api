<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
        <th>Date</th>
        <th>Condition</th>
        <th>Remarks</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->date)) {{date('d-M-Y',strtotime($lv->values->date))}} @endif</td>
		 		<td> @if(!empty($lv->values->condition)) {{$lv->values->condition->name}} @endif 
		 		</td>		
		 		<td> @if(!empty($lv->values->remarks)) {{$lv->values->remarks}} @endif
		 		</td>
		 	</tr>
		 	</tbody>    		
    @endforeach
 </table>