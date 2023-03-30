@extends('pdf_m.layouts.pdf_layouts')
@section('content')

<style type="text/css">
	td,th, body{
		font-size: 12px;
	}
	h2{
		font-size: 14px;
	}
</style>

		<?php 
			$gender = ''; 
		?>

 

    
    @foreach($patientDetails as $k => $v)


    <h1 align="center" class="color_secondary">{{$v->hospital_name}}</h1>
    <h3 align="center" >Continuous Summary Reports</h3>

    <?php $gender = $v->user->gender; ?>
	 <table>
	 	<tr>
	 		<th>Patient Name</th>
	 		<td colspan="3">{{$v->user->first_name}} {{$v->user->last_name}}</td>
	 	</tr>
	 	<tr>
	 		<th>UHID</th>
	 		<td colspan="3">{{$v->additional_info->uhid_number}}</td>
	 	</tr>
	 	<tr>
	 		<th>Gender</th>
	 		<td>{{$v->user->gender}}</td>
	 		<th>Date of Birth</th>
	 		<td>@if(!empty($v->user->dob)) {{date('d-M-Y',strtotime($v->user->dob))}} @endif</td>
	 	</tr>
	 	<tr>
	 		<th>Email </th>
	 		<td>{{$v->user->email}}</td>
	 		<th>Contact no.</th>
	 		<td>{{$v->user->isd_code}} {{$v->user->mobile}}</td>
	 	</tr>
	 	<tr>
	 		<th>Address</th>
	 		<td colspan="3">
	 			@if(!empty($v->user->address->address1)) {{$v->user->address->address1}} @endif
	 			@if(!empty($v->user->address->address2)) {{$v->user->address->address2}}, @endif
	 			@if(!empty($v->user->address->city)) {{$v->user->address->city}} - @endif
	 			@if(!empty($v->user->address->zipcode)) {{$v->user->address->zipcode}}, @endif
	 			@if(!empty($v->user->address->state)) {{$v->user->address->state}}, @endif
	 			@if(!empty($v->user->address->country)) {{$v->user->address->country}} @endif
	 			</td>
	 	</tr>
	 </table>

@endforeach


	<h3 class="color_primary">Vitals:</h3>

	<?php
		$vitals = [];
		$evaluation = [];
		$examination = [];
		$assessment = [];

		$orderVitals = ['temperature','bp','pulse','respiration'];

		foreach ($orderVitals as $key) {
		    $vitals[$key] = $patientHealth->values->vitals->$key;
		}

		if(!empty($patientHealth->values->assessment)){
			$assessment = $patientHealth->values->assessment;
		}

		if(!empty($patientHealth->values->examination)){
			$examination = $patientHealth->values->examination;
		}

		if(!empty($patientHealth->values->evaluation)){
			$evaluation = $patientHealth->values->evaluation;
		}
	?>

   @foreach($vitals as $k => $v)
   	
	   	@switch($k)

			    @case('temperature')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Temperature</h4>
			        @component('pdf_m.pdf_components.temperature',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('bp')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Blood Pressure</h4>
			        @component('pdf_m.pdf_components.blood_pressure',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('pulse')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Heart Rate</h4>
			        @component('pdf_m.pdf_components.heart_rate',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('respiration')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Respiration</h4>
			        @component('pdf_m.pdf_components.respiration',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @default
		        @break
			@endswitch
   @endforeach

   		@if(count((array)$evaluation) > 0)
    		<h4 class="color_secondary">HPI</h4>
	        @component('pdf_m.pdf_components.hpi',['lists'=> $evaluation->hpi])
			@endcomponent
		 @endif


	    @if(count((array)$examination) > 0)
	    	<h4 class="color_secondary">Examination</h4>
	        @component('pdf_m.pdf_components.examination',['lists'=> $examination,'gender'=>$gender])
			@endcomponent
		@endif


	    @if(count((array)$assessment) > 0)
	    	<h4 class="color_secondary">Assessment</h4>
	        @component('pdf_m.pdf_components.examination',['lists'=> $assessment,'gender'=>$gender])
			@endcomponent
		@endif




@stop