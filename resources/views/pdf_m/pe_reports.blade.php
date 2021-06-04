@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
	
	@component('pdf_m.pdf_components.title',['title'=> ucfirst('Physical Examination').' Reports'])
    @endcomponent

    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $content['patient_details']])
    @endcomponent


	<h3 class="color_primary">Results:</h3>

    @foreach($content['pe_info'] as $k => $v)
	    	
		         @component('pdf_m.pdf_components.ros',['lists'=> $v])
	    		 @endcomponent
		       
     		
    @endforeach
	 
@stop