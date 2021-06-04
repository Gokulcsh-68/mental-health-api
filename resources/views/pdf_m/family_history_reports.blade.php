@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	
	@component('pdf_m.pdf_components.title',['title'=> ucfirst($request->get('slug')).' Reports'])
    @endcomponent
    
    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content->patient_details])
    @endcomponent


	<h3 class="color_primary">Results:</h3>

    @foreach($content->data_info as $k => $v)
   	
	   @component('pdf_m.pdf_components.family_history',['lists'=> $v])
	   @endcomponent  

     		
    @endforeach
	 
@stop