@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	
	@component('pdf_m.pdf_components.title',['title'=> ucfirst($request->get('slug')).' Reports'])
    @endcomponent

    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content['patient_details']])
    @endcomponent


	<h3 class="color_primary">Results:</h3>

    @foreach($content['history_info'] as $k => $v)


	    @if(!empty($v['providers']))
	    @component('pdf_m.pdf_components.provider_details',['provider_details'=> [$v['providers']]])
	    @endcomponent
	    @else
			<h4 class="color_secondary">Self Added:</h4>
	    @endif

	    @switch($request->get('slug'))
		    @case('medical-history')
		         @component('pdf_m.pdf_components.medical_history',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('surgical-history')
		        @component('pdf_m.pdf_components.surgical_history',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('social-history')
		        @component('pdf_m.pdf_components.social_history',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('stroke-scale')
		        @component('pdf_m.pdf_components.stroke_scale',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @default
		        <span>`{{ucfirst($request->get('slug'))}}` is not found!</span>
		@endswitch
	   

     		
    @endforeach
	 
@stop