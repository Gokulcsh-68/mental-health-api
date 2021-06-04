@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	
	@component('pdf_m.pdf_components.title',['title'=> ucfirst($request->get('slug')).' Reports'])
    @endcomponent

    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content['patient_details']])
    @endcomponent


	<h3 class="color_primary">Results:</h3>

    @foreach($content['docs_info'] as $k => $v)


	    @if(!empty($v['providers']))
	    @component('pdf_m.pdf_components.provider_details',['provider_details'=> [$v['providers']]])
	    @endcomponent
	    @else
			<h4 class="color_secondary">Self Added:</h4>
	    @endif

	    @switch($request->get('slug'))
		    @case('lab')
		         @component('pdf_m.pdf_components.lab',['lists'=> $v['lists']])
	    		 @endcomponent
		        @break

		    @case('imaging')
		        @component('pdf_m.pdf_components.imaging',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('documents')
		        @component('pdf_m.pdf_components.documents',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('notes')
		        @component('pdf_m.pdf_components.notes',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('icd')
		        @component('pdf_m.pdf_components.icd',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('chief-complaints')
		        @component('pdf_m.pdf_components.chief_complaints',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @case('health-insurance')
		        @component('pdf_m.pdf_components.health_insurance',['lists'=> $v['lists']])
	    		@endcomponent
		        @break

		    @default
		        <span>`{{ucfirst($request->get('slug'))}}` is not found!</span>
		@endswitch
	   

     		
    @endforeach
	 
@stop