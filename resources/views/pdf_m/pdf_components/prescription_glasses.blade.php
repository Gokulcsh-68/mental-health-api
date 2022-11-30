<style type="text/css">
	.prescription_table th,.prescription_table td{
		font-size: 14px;
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

		@foreach($val->values as $lk => $lv)


		<?php
			$reading = [];
			$title = '';

			if(!empty($lv->re)){
				$reading = $lv->re;
				$title = "Right Eye (R.E.)";				
			}

			if(!empty($lv->le)){
				$reading = $lv->le;
				$title = "Left Eye (L.E.)";
			}
		?>
		
		<h4>{{$title}}</h4>

		<table id="table" class="prescription_table" style="margin-top: 15px;">
			<thead>
			 	<tr>
		          <th>Rx</th>
		          <th>SPH</th>
		          <th>CYL</th>
		          <th>AXIS</th>
		        </tr>
			</thead>

			<tbody>

				@foreach($reading as $k => $v)
				<tr>
		          <td>{{$v->rx}}</td>
		          <td>{{$v->sph}}</td>
		          <td>{{$v->cyl}}</td>
		          <td>{{$v->axis}}</td>
		        </tr>
				@endforeach

			</tbody>
		</table>
		@endforeach

	@endforeach