<style type="text/css">
	.prescription_table th,.prescription_table td{
		font-size: 12px;
		text-align: center;
	}
</style>

	@foreach($lists as $key => $val)	
		<table style="margin-top: 15px;">
			<thead>
		 	<tr>
		 		<th>Prescription Id : {{$val->id}}</th>		 	
		 		<th>Date : @if(!empty($val->created_at)) {{date('d M Y H:i',strtotime($val->created_at))}} @endif </th>
		 	</tr>
			</thead>
		</table>

		<table id="table" class="prescription_table" style="margin-top: 15px;">
			<thead>
		 	<tr>
		 		<th rowspan="2" colspan="2">Rx</th>
		 		<th style="width: 75px">Frequency</th>
		 		<th rowspan="2">Duration</th>
		 		<th rowspan="2">Qty / Taken</th>
		 	</tr>
		 	<tr>
		 		<th>(MN - AF - EN - NT)</th>
		 	</tr>
		</thead>

		<tbody>

		@foreach($val->values as $lk => $lv)	
		 	<tr>
		 		<td style="width:25px">{{$lk+1}}</td>
		 		<td> 
		 			@if(!empty($lv->medicine_name)) {{$lv->medicine_name}} @endif
		 			<br>
		 			@if(!empty($lv->medicine_instruction)) ({{$lv->medicine_instruction}}) @endif
		 		</td>
		 		<td> 
		 			@if(!empty($lv->medicine_taken->m)) {{$lv->medicine_taken->m}}  @else 0 @endif - 
		 			@if(!empty($lv->medicine_taken->a)) {{$lv->medicine_taken->a}}  @else 0 @endif - 
		 			@if(!empty($lv->medicine_taken->e)) {{$lv->medicine_taken->e}}  @else 0 @endif - 
		 			@if(!empty($lv->medicine_taken->n)) {{$lv->medicine_taken->n}}  @else 0 @endif
		 			<br>
					@if(!empty($lv->medicine_takenat))

						    @switch($lv->medicine_takenat)
							    @case('AF')
							        <span> AFTER FOOD</span>
							        @break

							    @case('BF')
							        <span>BEFORE FOOD</span>
							        @break

							    @default
							        
							@endswitch

					@endif
		 		</td>
		 		<td>
		 			@if(!empty($lv->total_medicine_duration)) {{$lv->total_medicine_duration}} @endif
		 		</td>
		 		<td>
		 			@if(!empty($lv->total_qty)) {{$lv->total_qty}} @endif
		 		</td>
		 	</tr>	
    	@endforeach
		</tbody>		
    @endforeach
	
 </table>