<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Values</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	@foreach($lv->values as $lkk => $lvv)
	<tbody>
		 	<tr>
		 		<td> {{$lkk}} :
		 			 <b>{{$lvv}} </b>
		 		</td>		 		
		 	</tr>
		 </tbody>
    @endforeach
    @endforeach
 </table>