@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	
	@component('pdf_m.pdf_components.title',['title'=> ucfirst($request->get('act_catagory')).' Reports'])
    @endcomponent

    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content['patient_details']])
    @endcomponent


	<h3 class="color_primary">Results:</h3>

    @foreach($content['data_info'] as $k => $v)

	    @switch($request->get('act_catagory'))
		    @case('activity')
		         @component('pdf_m.pdf_components.activity',['lists'=> $v])
	    		 @endcomponent
		        @break

		    @case('fluid')
		        @component('pdf_m.pdf_components.fluid',['lists'=> $v])
	    		@endcomponent
		        @break

		    @case('food')
		        @component('pdf_m.pdf_components.food',['lists'=> $v])
	    		@endcomponent
		        @break

		    @case('sleep')
		        @component('pdf_m.pdf_components.sleep',['lists'=> $v])
	    		@endcomponent
		        @break

		    @case('mood')
		        @component('pdf_m.pdf_components.mood',['lists'=> $v])
	    		@endcomponent
		        @break

		    @default
		        <span>`{{ucfirst($request->get('act_catagory'))}}` is not found!</span>
		@endswitch
	   

     		
    @endforeach
	 
@stop