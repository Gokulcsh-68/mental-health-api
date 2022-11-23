<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
        <th>Date</th>
        <th>Details</th>
        <th>Remarks</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)		
		<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->values->date)) {{date('d-M-Y',strtotime($lv->values->date))}} @endif</td>
		 		<td> @if(!empty($lv->values->tobacco)) Tobacco, @endif 
		 			 @if(!empty($lv->values->nontobacco)) Non Tobacco, @endif
		 			 @if(!empty($lv->values->alcohol)) Alcohol, @endif 
		 			 @if(!empty($lv->values->drug)) Drug, @endif 
		 			 @if(!empty($lv->values->exercise)) Exercise, @endif 
		 			 @if(!empty($lv->values->caffeine)) Caffeine, @endif 
		 			 @if(!empty($lv->values->diet)) Diet, @endif 
		 			 @if(!empty($lv->values->sexually_activity)) Sexually Active @endif 
		 		</td>		
		 		<td> @if(!empty($lv->values->notes)) {{$lv->values->notes}} @endif
		 		</td>
		 	</tr>
		 	</tbody>    		
    @endforeach
 </table>