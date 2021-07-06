	@foreach($lists as $lk => $lv)
			
	@if(!empty($lv->values->tobacco))
	@if($lv->values->tobacco)
	@if(!empty($lv->values->tobacco_details))
	<h4 style="text-decoration: underline;">Tobacco</h4>
	 <table>
	 	<tr>
	 		<th>Oral / Chewing</th>
	 		<td>@if(!empty($lv->values->tobacco_details->tobacco_oral)) @if($lv->values->tobacco_details->tobacco_oral) Yes @endif  @endif</td>
	 		<th>Smoking</th>
	 		<td colspan="3">@if(!empty($lv->values->tobacco_details->tobacco_smoking)) @if($lv->values->tobacco_details->tobacco_smoking) Yes @endif  @endif</td>	 		
	 	</tr> 	

	 	<tr>
	 		<th>Cigarette Type</th>
	 		<td>@if(!empty($lv->values->tobacco_details->cigarette_type)) {{$lv->values->tobacco_details->cigarette_type}}  @endif</td>
	 		<th>No. of Cigarette</th>
	 		<td>@if(!empty($lv->values->tobacco_details->smoke_nos))  {{$lv->values->tobacco_details->smoke_nos}}  @endif</td>
	 		<th>Frequency</th>
	 		<td>@if(!empty($lv->values->tobacco_details->smoke_frequency_type)) {{$lv->values->tobacco_details->smoke_frequency_type}} @endif</td>	 		
	 	</tr> 

	 	<tr>
	 		<th>No. of Years Smoking</th>
	 		<td>@if(!empty($lv->values->tobacco_details->ex_smoker_no_of_years)) {{$lv->values->tobacco_details->ex_smoker_no_of_years}}  @endif</td>	 
	 		<th>Years Stopped</th>
	 		<td colspan="3">@if(!empty($lv->values->tobacco_details->ex_smoker_year_of_stop)) {{$lv->values->tobacco_details->ex_smoker_year_of_stop}}  @endif</td>		
	 	</tr> 

	 	<tr>
	 		<th>Notes</th>
	 		<td colspan="5">@if(!empty($lv->values->tobacco_details->smoking_notes)) {{ $lv->values->tobacco_details->smoking_notes }}  @endif</td>	
	 	</tr> 
	 </table>
	 @endif
	 @endif
	 @endif


	 @if(!empty($lv->values->alcohol))
	 @if($lv->values->alcohol)
	 @if(!empty($lv->values->alcohol_details))
	<h4 style="text-decoration: underline;">Alcohol </h4>
	 <table>
	 	<tr>
	 		<th>Alcohol Type</th>
	 		<td>@if(!empty($lv->values->alcohol_details->alcohol_type)) {{$lv->values->alcohol_details->alcohol_type}}  @endif</td>
	 		<th>No. of Drinks</th>
	 		<td>@if(!empty($lv->values->alcohol_details->alcohol_nos_drinks))  {{$lv->values->alcohol_details->alcohol_nos_drinks}}  @endif</td>
	 		<th>Frequency</th>
	 		<td>@if(!empty($lv->values->alcohol_details->alcohol_frequency_type)) {{$lv->values->alcohol_details->alcohol_frequency_type}} @endif</td>	 		
	 	</tr> 

	 	<tr>
	 		<th>No. of Years</th>
	 		<td>@if(!empty($lv->values->alcohol_details->alcohol_year_started)) {{$lv->values->alcohol_details->alcohol_year_started}}  @endif</td>	 
	 		<th>Years Stopped</th>
	 		<td colspan="3">@if(!empty($lv->values->alcohol_details->alcohol_year_ended)) {{$lv->values->alcohol_details->alcohol_year_ended}}  @endif</td>		
	 	</tr> 

	 	<tr>
	 		<th>Notes</th>
	 		<td colspan="5">@if(!empty($lv->values->alcohol_details->any_other_drugs_remarks)) {{ $lv->values->alcohol_details->any_other_drugs_remarks }}  @endif</td>	
	 	</tr> 
	 </table>
	 @endif
	 @endif
	 @endif



	 @if(!empty($lv->values->drug))
	 @if($lv->values->drug)
	 @if(!empty($lv->values->drug_details))
	<h4 style="text-decoration: underline;">Drug </h4>
	 <table>
	 	<tr>
	 		<th>Drug Name</th>
	 		<td>@if(!empty($lv->values->drug_details->drug_name)) {{$lv->values->drug_details->drug_name}}  @endif</td>
	 		<th>In Take</th>
	 		<td>@if(!empty($lv->values->drug_details->route_of_intake))  {{$lv->values->drug_details->route_of_intake}}  @endif</td>
	 		<th>Drug Remarks</th>
	 		<td>@if(!empty($lv->values->drug_details->drug_remarks)) {{$lv->values->drug_details->drug_remarks}} @endif</td>	 		
	 	</tr> 

	 	<tr>
	 		<th>No. of Years</th>
	 		<td>@if(!empty($lv->values->drug_details->drug_year_started)) {{$lv->values->drug_details->drug_year_started}}  @endif</td>	 
	 		<th>Years Stopped</th>
	 		<td colspan="3">@if(!empty($lv->values->drug_details->drug_year_ended)) {{$lv->values->drug_details->drug_year_ended}}  @endif</td>		
	 	</tr> 

	 </table>
	 @endif
	 @endif
	 @endif




	 @if(!empty($lv->values->exercise))
	 @if($lv->values->exercise)
	 @if(!empty($lv->values->exercise_details))
	<h4 style="text-decoration: underline;">Exercise </h4>
	 <table>

	 	<tr>
	 		<th>Exercise</th>
	 		<td>@if(!empty($lv->values->exercise_details->exercise_notes)) {{$lv->values->exercise_details->exercise_notes}}  @endif</td>	 	
	 	</tr> 

	 </table>
	 @endif
	 @endif
	 @endif

	 @if(!empty($lv->values->occupation))
	 @if($lv->values->occupation)
	 @if(!empty($lv->values->occupation_details))
	<h4 style="text-decoration: underline;">Occupation </h4>
	 <table>

	 	<tr>
	 		<th>Occupation</th>
	 		<td>
	 			@if(!empty($lv->values->occupation_details->occupation_name->name)) {{$lv->values->occupation_details->occupation_name->name}} 

	 			@else (!empty($lv->values->occupation_details->occupation_name)) {{$lv->values->occupation_details->occupation_name}}  @endif	 			
	 		</td> 	
	 	</tr> 
	 	<tr>
	 		<th>Occupation Notes</th>
	 		<td>@if(!empty($lv->values->occupation_details->occupation_notes)) {{$lv->values->occupation_details->occupation_notes}}  @endif</td>	 	
	 	</tr> 

	 </table>
	 @endif
	 @endif
	 @endif


	 @if(!empty($lv->values->caffeine))
	 @if($lv->values->caffeine)
	 @if(!empty($lv->values->caffeine_details))
	<h4 style="text-decoration: underline;">Caffeine </h4>
	 <table>

	 	<tr>
	 		<th>Caffeine</th>
	 		<td>@if(!empty($lv->values->caffeine_details->no_of_cups)) {{$lv->values->caffeine_details->no_of_cups}}  @endif</td> 	
	 	</tr> 
	 	<tr>
	 		<th>Caffeine Notes</th>
	 		<td>@if(!empty($lv->values->caffeine_details->coffeine_notes)) {{$lv->values->caffeine_details->coffeine_notes}}  @endif</td>	 	
	 	</tr> 

	 </table>
	 @endif
	 @endif
	 @endif



	 @if(!empty($lv->values->sexually_activity))
	 @if($lv->values->sexually_activity)
	 @if(!empty($lv->values->activity_details))
	<h4 style="text-decoration: underline;">Sexually Active </h4>
	 <table>

	 	<tr>
	 		<th>Notes</th>
	 		<td>@if(!empty($lv->values->activity_details->sexually_activity_notes)) {{$lv->values->activity_details->sexually_activity_notes}}  @endif</td> 	
	 	</tr> 

	 </table>
	 @endif
	 @endif
	 @endif



	 @if(!empty($lv->values->travel_out_of_country))
	 @if($lv->values->travel_out_of_country)
	 @if(!empty($lv->values->travel_details))
	<h4 style="text-decoration: underline;">Travel out of country </h4>
	 <table>

	 	<tr>
	 		<th>Notes</th>
	 		<td>@if(!empty($lv->values->travel_details->travel_out_of_country_notes)) {{$lv->values->travel_details->travel_out_of_country_notes}}  @endif</td> 	
	 	</tr> 

	 </table>
	 @endif
	 @endif
	 @endif



	 @if(!empty($lv->values->living_situation))
	 @if($lv->values->living_situation)
	 @if(!empty($lv->values->living_details))
	<h4 style="text-decoration: underline;">Living Situation </h4>
	 <table>

	 	<tr>
	 		<th>Living Details</th>
	 		<td>
	 			@if(!empty($lv->values->living_details->living_with_whom->name)) {{$lv->values->living_details->living_with_whom->name}} 

	 			@else (!empty($lv->values->living_details->living_with_whom)) {{$lv->values->living_details->living_with_whom}}  @endif
	 		</td> 
	 		</tr> 
	 		<tr>
	 		<th>Notes</th>
	 		<td>@if(!empty($lv->values->living_details->living_situation_notes)) {{$lv->values->living_details->living_situation_notes}}  @endif</td> 	
	 	</tr> 

	 </table>
	 @endif
	 @endif
	 @endif


	@endforeach