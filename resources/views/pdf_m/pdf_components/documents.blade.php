<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Title</th>
 		<th>Source</th>
 		<th>Notes</th>
 		<th>Link</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)		    		
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->addition_info->title)) {{$lv->addition_info->title}} @endif</td>
		 		<td> @if(!empty($lv->document_source)) {{$lv->document_source}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->notes)) {{$lv->addition_info->notes}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->document_link)) <a href="{{$lv->addition_info->document_link}}" target="_blank">Click Me</a> @endif </td>
		 	</tr>
		 	</tbody>
    @endforeach
 </table>