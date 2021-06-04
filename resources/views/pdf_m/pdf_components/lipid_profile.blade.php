<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Date</th>
 		<th>Time</th>
 		<th>LDL</th>
 		<th>HDL</th>
 		<th>VLDL</th>
 		<th>HDL / LDL</th>
 		<th>Triglycerides</th>
 		<th>Total</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->details->date)) {{date('d-M-Y',strtotime($lv->details->date))}} @endif</td>
		 		<td> @if(!empty($lv->details->time)) {{$lv->details->time}} @endif</td>
		 		<td> @if(!empty($lv->details->ldl)) {{$lv->details->ldl}} @endif 
		 			 @if(!empty($lv->details->ldl_unit)) {{$lv->details->ldl_unit}} @endif
		 			 @if(!empty($lv->details->ldl_message)) , {{$lv->details->ldl_message}} @endif
		 		</td>	
		 		<td> @if(!empty($lv->details->hdl)) {{$lv->details->hdl}} @endif 
		 			 @if(!empty($lv->details->hdl_unit)) {{$lv->details->hdl_unit}} @endif
		 			 @if(!empty($lv->details->hdl_message)) , {{$lv->details->hdl_message}} @endif
		 		</td>	
		 		<td> @if(!empty($lv->details->vldl)) {{$lv->details->vldl}} @endif 
		 			 @if(!empty($lv->details->vldl_unit)) {{$lv->details->vldl_unit}} @endif
		 			 @if(!empty($lv->details->vldl_message)) , {{$lv->details->vldl_message}} @endif
		 		</td>	
		 		<td> @if(!empty($lv->details->hdl_ldl)) {{$lv->details->hdl_ldl}} @endif 
		 			 @if(!empty($lv->details->hdl_ldl_unit)) {{$lv->details->hdl_ldl_unit}} @endif
		 			 @if(!empty($lv->details->hdl_ldl_message)) , {{$lv->details->hdl_ldl_message}} @endif
		 		</td>	
		 		<td> @if(!empty($lv->details->triglycerides)) {{$lv->details->triglycerides}} @endif 
		 			 @if(!empty($lv->details->triglycerides_unit)) {{$lv->details->triglycerides_unit}} @endif
		 			 @if(!empty($lv->details->triglycerides_message)) , {{$lv->details->triglycerides_message}} @endif
		 		</td>
		 		<td> @if(!empty($lv->details->total)) {{$lv->details->total}} @endif
		 			@if(!empty($lv->details->total_unit)) {{$lv->details->total_unit}} @endif
		 		</td>
		 	</tr>
		 </tbody>
    @endforeach
 </table>