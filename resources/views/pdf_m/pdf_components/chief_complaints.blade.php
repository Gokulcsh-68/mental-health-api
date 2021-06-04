<table id="table" style="margin-top: 15px;">
 	<thead>
 		<tr>
 		<th>Complaints</th>
 		<th>Notes</th>
 	</tr>
 	</thead>
	@foreach($lists as $lk => $lv)	
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->addition_info->title)) {{$lv->addition_info->title}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->notes)) {{$lv->addition_info->notes}} @endif</td>
		 	</tr>
		</tbody>	    		
    @endforeach
 </table>