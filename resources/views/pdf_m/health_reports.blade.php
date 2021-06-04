@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	
	@component('pdf_m.pdf_components.title',['title'=> ucfirst($request->get('slug')).' Reports'])
    @endcomponent

    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content['patient_details']])
    @endcomponent


	<h3 class="color_primary">Results:</h3>

    @foreach($content['medicine_info'] as $k => $v)


	    @if(!empty($v['providers']))
	    @component('pdf_m.pdf_components.provider_details',['provider_details'=> [$v['providers']]])
	    @endcomponent
	    @else
			<h4 class="color_secondary">Self Added:</h4>
	    @endif

	    @switch($request->get('slug'))
		    @case('medicine')
		         @component('pdf_m.pdf_components.medicine',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('allergy')
		        @component('pdf_m.pdf_components.allergy',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('diet')
		        @component('pdf_m.pdf_components.diet',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('hpi')
		        @component('pdf_m.pdf_components.hpi',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('procedure')
		        @component('pdf_m.pdf_components.procedure',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @default
		        <span>`{{ucfirst($request->get('slug'))}}` is not found!</span>
		@endswitch
	   

     		
    @endforeach
	 
@stop