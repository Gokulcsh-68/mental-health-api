<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>BMI</th>
 		<th>Height</th>
 		<th>Weight</th>
 		<th>Date</th>
 		<th>Result</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->bmi)) {{$lv->details->bmi}} @endif</td>
		 		<td> @if(!empty($lv->details->height)) {{$lv->details->height}} @endif 
		 			 @if(!empty($lv->details->height_unit)) {{$lv->details->height_unit}} @endif
		 		</td>
		 		<td> @if(!empty($lv->details->weight)) {{$lv->details->weight}} @endif 
		 			 @if(!empty($lv->details->weight_unit)) {{$lv->details->weight_unit}} @endif
		 		</td>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->bmiFlag)) {{$lv->details->bmiFlag}} @endif</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>