<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>S No</th>
 		<th>Type</th>
 		<th>Status</th>
 		<th>Values</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)

	<tbody>
		 	<tr>
		 		<td>{{$lk+1}}</td>
		 		<td> @if(!empty($lv->name)) {{$lv->name}} @endif</td>
		 		<td> @if(!empty($lv->status)) {{ucfirst($lv->status)}} @endif </td>		
		 		<td> @if(!empty($lv->values)) 

		 			@foreach($lv->values as $kk => $vv)
		 				{{strtoupper($kk)}}, <br>

		 			@endforeach

		 		 @endif
		 		</td>

		 	</tr>
		 </tbody>
    @endforeach
 </table>
