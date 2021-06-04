@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	@foreach($content->form_info as $k => $v)

		@component('pdf_m.pdf_components.title',['title'=> ucfirst($v->name).' Reports'])
	    @endcomponent

    @endforeach
	    
	    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content->patient_details])
	    @endcomponent
	
	<h3 class="color_primary">Assessments:</h3>

    @foreach($content->form_info as $k => $v)
   		

	   @component('pdf_m.pdf_components.assessment',['lists'=> $v])
	   @endcomponent  

     		
    @endforeach
	 
@stop