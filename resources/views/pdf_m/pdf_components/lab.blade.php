<table id="table" style="margin-top: 15px;">
 	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Lab Test</th>
 		<th>Notes</th>
 		<th>Link</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)		    		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->addition_info->date)) {{date('d-M-Y',strtotime($lv->addition_info->date))}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->title)) {{$lv->addition_info->title}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->notes)) {{$lv->addition_info->notes}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->document_link)) <a href="{{$lv->addition_info->document_link}}" target="_blank">Click Me</a> @endif </td>
		 	</tr>
		 	</tbody>
    @endforeach
 </table>