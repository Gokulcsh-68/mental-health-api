

<?php 


$msg = 'Please Take Assessment';
if(!empty($lists->latest_form_submisson->message)){

	$msg = str_replace(['!success!','!primary!','!secondary!','!warning!','!danger!'],':',$lists->latest_form_submisson->message);

	$msg = str_replace(['@span','@f','@c','!','@br'],'',$msg);
	$msg = str_replace(['@teleconsult'],'please take a teleconsult',$msg);

}

 ?>


 <h4><span class="color_primary">Results:</span> {{$msg}} </h4>
   		

	@foreach($lists->questions as $lk => $lv)
	
  <div>
    <div class="col-md-12">
      <h4 class="font-weight-bold mb-5 mt-5">
      	@if(count($lists->questions)> 1)
        <span> Q{{ $lk + 1 }}. </span> 
         @endif
        {{ $lv->question->name }}
      </h4>

      @if($lv->question->type == 'sub_question')
      <div>

      	@component('pdf_m.pdf_components.assessment_sub_question',[
      	'subQuestions'=> $lv->question->sub_questions,
      	'submittedAnswer'=> $lists->latest_form_submisson,
      	'itemAnswers'=> $lv->answers,
      	'questionId'=> $lv->question->id
      	])
    	@endcomponent
      </div>
      @endif

      @if($lv->question->type != 'sub_question')
      
        
    	<div style="margin-left: 15px;">

        @component('pdf_m.pdf_components.assessment_answer',[
      	'sub_question'=> 'no',
      	'itemAnswers'=> $lv->answers,
      	'questionId'=> $lv->question->id,
      	'answerType'=> $lv->question->type,
      	'submittedAnswer'=> $lists->latest_form_submisson
      	])
    	@endcomponent


      </div>
      @endif
    </div>
  </div>
  @endforeach