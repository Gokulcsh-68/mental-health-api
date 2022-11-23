<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
        <th>Date</th>
        <th>Procedure</th>
        <th>Notes</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->date)) {{date('d-M-Y',strtotime($lv->values->date))}} @endif</td>
		 		<td> @if(!empty($lv->values->name)) {{$lv->values->name}} @endif 
		 		</td>		
		 		<td> @if(!empty($lv->values->notes)) {{$lv->values->notes}} @endif
		 		</td>
		 	</tr>
		 	</tbody>    		
    @endforeach
 </table>