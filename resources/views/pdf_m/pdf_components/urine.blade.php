<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>Urine</th>
 		<th>Sugar</th>
 		<th>Leukocytes</th>
 		<th>Protein</th>
 		<th>RBC</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->urine)) {{$lv->details->urine}} pH @endif 
		 			 @if(!empty($lv->details->value)) {{$lv->details->value}} @endif
		 		</td>		 		
		 		<td> @if(!empty($lv->details->sugar)) {{$lv->details->sugar}} @endif
		 			@if(!empty($lv->details->sugar_flag)) {{$lv->details->sugar_flag}} @endif
		 		</td>	 		
		 		<td> @if(!empty($lv->details->leukocytes)) {{$lv->details->leukocytes}} @endif
		 			@if(!empty($lv->details->leukocytes_message)) {{$lv->details->leukocytes_message}} @endif
		 		</td> 		
		 		<td> @if(!empty($lv->details->protein)) {{$lv->details->protein}} @endif
		 			@if(!empty($lv->details->protein_message)) {{$lv->details->protein_message}} @endif
		 		</td>	
		 		<td> @if(!empty($lv->details->rbc)) {{$lv->details->rbc}} @endif
		 			@if(!empty($lv->details->rbc_message)) {{$lv->details->rbc_message}} @endif
		 		</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>