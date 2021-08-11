@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	
	@component('pdf_m.pdf_components.title',['title'=> ucfirst($request->get('slug')).' Reports'])
    @endcomponent

    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content['patient_details']])
    @endcomponent

    <?php

    	$gender = '';

    	foreach ($content['patient_details'] as $key => $value) {
    		$gender = $value->gender;
    	}

    ?>
	<h3 class="color_primary">Results:</h3>

    @foreach($content['vitals_info'] as $k => $v)


	    @if(!empty($v['providers']))
	    @component('pdf_m.pdf_components.provider_details',['provider_details'=> [$v['providers']]])
	    @endcomponent
	    @else
			<h4 class="color_secondary">Self Added:</h4>
	    @endif

	    @switch($request->get('slug'))
		    @case('bmi')
		         @component('pdf_m.pdf_components.bmi',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('temperature')
		        @component('pdf_m.pdf_components.temperature',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('blood-sugar')
		        @component('pdf_m.pdf_components.blood_sugar',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('spO2')
		        @component('pdf_m.pdf_components.spO2',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('urine')
		        @component('pdf_m.pdf_components.urine',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('blood-pressure')
		        @component('pdf_m.pdf_components.blood_pressure',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('heart-rate')
		        @component('pdf_m.pdf_components.heart_rate',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('lipid-profile')
		        @component('pdf_m.pdf_components.lipid_profile',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('respiration')
		        @component('pdf_m.pdf_components.respiration',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('hct')
		        @component('pdf_m.pdf_components.hct',['lists'=> $v['lists'],'gender'=>$gender])
	    		 @endcomponent
		        @break

		    @case('hemoglobin')
		        @component('pdf_m.pdf_components.hemoglobin',['lists'=> $v['lists'],'gender'=>$gender])
	    		 @endcomponent
		        @break

		    @case('keytone')
		        @component('pdf_m.pdf_components.keytone',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('uric_acid')
		        @component('pdf_m.pdf_components.uric_aid',['lists'=> $v['lists'],'gender'=>$gender])
	    		 @endcomponent
		        @break

		    @default
		        <span>`{{ucfirst($request->get('slug'))}}` is not found!</span>
		@endswitch
	   

     		
    @endforeach
	 
@stop