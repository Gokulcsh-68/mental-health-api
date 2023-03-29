@extends('pdf_m.layouts.pdf_layouts')
@section('content')

<style type="text/css">
	td,th{
		font-size: 12px;
	}
	h2{
		font-size: 14px;
	}
</style>
	
 <?php $gender = ''; ?>
 
	@component('pdf_m.pdf_components.title',['title'=> 'Continuous Summary Reports'])
    @endcomponent

    
    @foreach($patientDetails as $k => $v)

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



@stop