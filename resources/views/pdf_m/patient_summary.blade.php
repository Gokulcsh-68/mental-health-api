@extends('pdf_m.layouts.pdf_layouts')
@section('content')
	
 <?php $gender = ''; ?>
 
	@component('pdf_m.pdf_components.title',['title'=> 'Patient Summary Reports'])
    @endcomponent

   @foreach($content->profile as $k => $v)

   		@switch($k)
			    @case('a_profile')
					<?php
						$patient_details = (array) [$v];
						
				    	foreach ($patient_details as $key => $value) {
				    		$gender = $value->gender;
				    	}
					?>
				   
					    @component('pdf_m.pdf_components.patient_details',['patient_details'=> $patient_details])
					    @endcomponent
			    	@break
			    @case('c_provider_details')
			    		<?php
							$provider_details = (array) [(array)$v];
							?>
				   
					    @component('pdf_m.pdf_components.provider_details',['provider_details'=> $provider_details])
					    @endcomponent
			    	@break
			    	@case('b_consult_details')
			    		<?php
							$consult_details = (array) [(array)$v];
							// dd($consult_details);
							?>
				   
					    @component('pdf_m.pdf_components.consult_details',['consult_details'=> $consult_details])
					    @endcomponent
			    	@break
			    @default
		        @break
			@endswitch
	 @endforeach

	<h3 class="color_primary">Vitals:</h3>

   @foreach($content->vitals as $k => $v)
   	
	   	@switch($k)
			    @case('a_bmi')

				    @if(count((array)$v) > 0)
				    	<h4 class="color_secondary">BMI</h4>
				         @component('pdf_m.pdf_components.bmi',['lists'=> $v])
			    		 @endcomponent
		    		 @endif
			        @break

			    @case('b_temperature')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Temperature</h4>
			        @component('pdf_m.pdf_components.temperature',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('c_bs')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Blood Sugar</h4>
			        @component('pdf_m.pdf_components.blood_sugar',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('d_spo2')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">SPO2</h4>
			        @component('pdf_m.pdf_components.spO2',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('e_urine')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Urine</h4>
			        @component('pdf_m.pdf_components.urine',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('f_bp')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Blood Pressure</h4>
			        @component('pdf_m.pdf_components.blood_pressure',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('g_heart')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Heart Rate</h4>
			        @component('pdf_m.pdf_components.heart_rate',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('h_lipid')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Lipid Profile</h4>
			        @component('pdf_m.pdf_components.lipid_profile',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('i_respiration')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Respiration</h4>
			        @component('pdf_m.pdf_components.respiration',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('j_hct')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">HCT</h4>
			        @component('pdf_m.pdf_components.hct',['lists'=> $v,'gender'=>$gender])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('k_hemoglobin')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Hemoglobin</h4>
			        @component('pdf_m.pdf_components.hemoglobin',['lists'=> $v,'gender'=>$gender])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('l_keytone')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Keytone</h4>
			        @component('pdf_m.pdf_components.keytone',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('m_uric_acid')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Uric Acid</h4>
			        @component('pdf_m.pdf_components.uric_aid',['lists'=> $v,'gender'=>$gender])
		    		 @endcomponent
		    		 @endif
			        @break

			    @default
		        @break
			@endswitch
   @endforeach
	 


	<h3 class="color_primary">History:</h3>


   @foreach($content->history as $k => $v)
   	
	   	 @switch($k)
		    @case('a_medical_history')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Medical History</h4>
		         @component('pdf_m.pdf_components.medical_history',['lists'=> $v])
	    		 @endcomponent
		    		 @endif
		        @break

		    @case('h_diagnosis')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Diagnosis</h4>
		         @component('pdf_m.pdf_components.diagnosis',['lists'=> $v])
	    		 @endcomponent
		    		 @endif
		        @break

		    @case('b_surgical_history')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Surgical History</h4>
		        @component('pdf_m.pdf_components.surgical_history',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('c_social_history')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Social History</h4>
		        @component('pdf_m.pdf_components.habit_history',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		  <?php /*  @case('d_family_history')

			    	<h4 class="color_secondary">Family History</h4>
		        @component('pdf_m.pdf_components.family_history',['lists'=> $v])
	    		@endcomponent

		        @break */ ?> 

		    @case('e_ros')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Review of System</h4>
		        @component('pdf_m.pdf_components.ros',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('f_pe')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Physical Examination</h4>
		        @component('pdf_m.pdf_components.ros',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('g_stroke_scale')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Stroke Scale</h4>
		        @component('pdf_m.pdf_components.stroke_scale',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @default
		        @break
		        <!-- <span>`{{ucfirst($k)}}` is not found!</span> -->
		@endswitch

   @endforeach


	<h3 class="color_primary">Health:</h3>

	@foreach($content->health as $k => $v)
		@switch($k)


		    	@case('a_chief_complaints')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Chief Complaints</h4>
		        @component('pdf_m.pdf_components.chief_complaints',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break


			    @case('b_medicine')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Medicine</h4>
			         @component('pdf_m.pdf_components.medicine',['lists'=> $v])
		    		 @endcomponent
		    		 @endif
			        @break

			    @case('a_allergy')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Allergy</h4>
			        @component('pdf_m.pdf_components.allergy',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break

			    @case('c_diet')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Diet</h4>
			        @component('pdf_m.pdf_components.diet',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break

			    @case('d_hpi')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">HPI</h4>
			        @component('pdf_m.pdf_components.hpi',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break

			    @case('e_procedure')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Procedure</h4>
			        @component('pdf_m.pdf_components.procedure',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break

			    @case('f_vdx')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Diagnosis</h4>
			        @component('pdf_m.pdf_components.vdx',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break

			    @case('f_symptoms_reason')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Assessment & Evaluation</h4>
			        @component('pdf_m.pdf_components.symptoms_reason',['lists'=> $v,'gender'=>$gender])
		    		@endcomponent
		    		 @endif
			        @break

			    @case('g_surgical_procedure')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Surgical Procedure</h4>
			        @component('pdf_m.pdf_components.surgical_procedure',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break
			        
			    @case('h_examination')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Examination</h4>
			        @component('pdf_m.pdf_components.examination',['lists'=> $v,'gender'=>$gender])
		    		@endcomponent
		    		 @endif
			        @break
			        
			    @case('h_prescription')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Prescription</h4>
			        @component('pdf_m.pdf_components.prescription',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break
			        
			    @case('i_prescription_glasses')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Glasses</h4>
			        @component('pdf_m.pdf_components.prescription_glasses',['lists'=> $v])
		    		@endcomponent
		    		 @endif
			        @break

			    @default
		        @break
			@endswitch
   	@endforeach


<?php /*   	<h3 class="color_primary">Vaccination:</h3>

    @foreach($content->immunisation as $k => $v)
   	
	   @component('pdf_m.pdf_components.immunisation',['lists'=> $v])
	   @endcomponent

     		
    @endforeach */ ?> 


	<h3 class="color_primary">Documents:</h3>

	@foreach($content->docs as $k => $v)
		@switch($k)
		    @case('a_lab')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Lab</h4>
		         @component('pdf_m.pdf_components.lab',['lists'=> $v])
	    		 @endcomponent
		    		 @endif
		        @break

		    @case('b_imaging')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Imaging</h4>
		        @component('pdf_m.pdf_components.imaging',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('documents')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Documents</h4>
		        @component('pdf_m.pdf_components.documents',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('e_notes')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Notes</h4>
		        @component('pdf_m.pdf_components.notes',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('c_icd')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">ICD10 Diagnosis</h4>
		        @component('pdf_m.pdf_components.icd',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('d_chief_complaints')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Chief Complaints</h4>
		        @component('pdf_m.pdf_components.chief_complaints',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @case('e_health_insurance')
				    @if(count((array)$v) > 0)
			    	<h4 class="color_secondary">Health Insurance</h4>
		        @component('pdf_m.pdf_components.health_insurance',['lists'=> $v])
	    		@endcomponent
		    		 @endif
		        @break

		    @default
		        @break
		@endswitch
	@endforeach

@stop