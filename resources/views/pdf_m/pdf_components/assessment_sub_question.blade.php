
@foreach($subQuestions as $lsk => $lsv)
<div style="margin-left: 15px;">
    <div>
      <h4 class="color_secondary"> S{{ $lsk + 1 }}. {{ $lsv->name }} </h4>
    </div>

    <div style="margin-left: 15px;">
    @component('pdf_m.pdf_components.assessment_answer',[
      	'sub_question'=> $lsv,
      	'itemAnswers'=> $itemAnswers,
      	'questionId'=> $lsv->id,
      	'answerType'=> $lsv->type,
      	'submittedAnswer'=> $submittedAnswer
      	])
    	@endcomponent
    </div>
  
  </div>
@endforeach