

<?php $familyRelationships = ['Grandparents', 'Father', 'Mother', 'Brother(s)', 'Sister(s)', 'Daughter(s)', 'Son(s)'];
 ?>
 
<table id="table" style="margin-top: 15px;">
    		<thead>
          <tr class="bg-primary-o-25">
            <th>Disease</th>
            @foreach($familyRelationships as $fk => $fv)            	
            <th>
              {{ $fv }}
            </th>
            @endforeach
          </tr>
        </thead>
        @foreach($lists as $lk => $lv) 
	        <tbody>

	          <tr>
	          	<td>{{$lv->name}}</td> 
	            @foreach($familyRelationships as $fk => $fv) 

		          	@foreach($lv->attributes->values as $vk =>$vv)

		          		@if($fv == $vv->relationship)

		            		<td>@if($vv->status) Yes @endif</td> 

		            	@endif

		            @endforeach

	            @endforeach

	          </tr>

	        </tbody>
        @endforeach
    
</table>
