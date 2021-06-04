<table id="table" style="margin-top: 15px;">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Vaccine</th>
        <th>Period</th>
        <th>Type</th>
        <th>Status</th>
        <th>Dosage date</th>
        <th>Taken at</th>
      </tr>
    </thead>
    	@foreach($lists as $lk => $lv)		
    		<tbody>
    			<tr>
    				@if(count($lv->attributes->values) > 1)
    					<th class="bg" rowspan="{{count($lv->attributes->values)+1}}">{{$lk+1}}</th>
    					<th class="bg" rowspan="{{count($lv->attributes->values)+1}}">{{$lv->name}}</th>
    				@else
    					<th class="bg">{{$lk+1}}</th>
    					<th class="bg">{{$lv->name}}</th>
    					@foreach($lv->attributes->values as $lkk => $lvv)		
		    			
		    			<td>{{$lvv->periods}}</td>
	    				<td>{{$lvv->treatment}}</td>
		    			<td>@if($lvv->status) <span class="color_primary">Taken</span> @else <span class="color_secondary"> Not-Yet </span> @endif</td>
	    				<td>@if(!empty($lvv->dosage_date)) {{date('d-M-Y',strtotime($lvv->dosage_date))}} @endif</td>
	    				<td>@if(!empty($lvv->taken_at)) {{date('d-M-Y',strtotime($lvv->taken_at))}} @endif</td>
		    			
		    			@endforeach
    				@endif
    				
    			</tr>
    			@if(count($lv->attributes->values) > 1)
	    			@foreach($lv->attributes->values as $lkk => $lvv)		
	    			<tr>
	    				<td>{{$lvv->periods}}</td>
	    				<td>{{$lvv->treatment}}</td>
		    			<td>@if($lvv->status) <span class="color_primary">Taken</span> @else <span class="color_secondary"> Not-Yet </span> @endif</td>
	    				<td>@if(!empty($lvv->dosage_date)) {{date('d-M-Y',strtotime($lvv->dosage_date))}} @endif</td>
	    				<td>@if(!empty($lvv->taken_at)) {{date('d-M-Y',strtotime($lvv->taken_at))}} @endif</td>
	    			</tr> 
	    			@endforeach
    			@endif
    		</tbody>
		@endforeach
</table>
