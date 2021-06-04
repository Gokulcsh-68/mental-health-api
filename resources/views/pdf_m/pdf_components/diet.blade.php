<table id="table" style="margin-top: 15px;">
 <thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Category</th>
 		<th>Type</th>
 		<th>Food</th>
 		<th>Intake</th>
 	</tr>
 </thead>

	@foreach($lists as $lk => $lv)		
	<tbody>    		
		 	<tr>
		 		<td> @if(!empty($lv->values->date)) {{date('d-M-Y',strtotime($lv->values->date))}} @endif</td>
		 		<td> @if(!empty($lv->values->time)) {{$lv->values->time}} @endif</td>
		 		<td> @if(!empty($lv->values->category)) {{$lv->values->category}} @endif </td>
		 		<td> @if(!empty($lv->values->type)) {{$lv->values->type}} @endif</td>
		 		<td> @if(!empty($lv->values->food)) {{$lv->values->food}} @endif</td>
		 		<td> @if(!empty($lv->values->intake)) {{$lv->values->intake}} @endif @if(!empty($lv->values->unit)) ({{$lv->values->unit}}) @endif</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>