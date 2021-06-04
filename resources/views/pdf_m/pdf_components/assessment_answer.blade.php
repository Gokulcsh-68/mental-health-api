<style type="text/css">
  .color_primary{
            color: #003281;
            font-weight: bold;
        } 
</style>
<div>
  <div>
    @foreach($itemAnswers as $i => $render_answers)
    <p> 


      @if(($submittedAnswer == null || ($submittedAnswer != null && (empty($submittedAnswer->answers->$questionId)))) && $render_answers->answer != null)

      <span> {{ $i + 1 }}. {{ $render_answers->answer->name }}
      </span>
      @endif


      @if($submittedAnswer != null &&
          $answerType == 'radio' &&
          $render_answers->answer != null)

      <span>
        @if(!empty($submittedAnswer->answers->$questionId))
        @if($render_answers->answer->id == $submittedAnswer->answers->$questionId->id)
        <span class="color_primary">
        {{ $i + 1 }}. {{ $render_answers->answer->name }}
        </span>
        @else
          <span class="">
         {{ $i + 1 }}. {{ $render_answers->answer->name }}
        </span>
        @endif
        @endif
      </span>
      @endif

       @if($submittedAnswer != null &&
          $answerType == 'input' &&
          $render_answers->answer != null)
      <span>
        @if($render_answers->answer->id == $submittedAnswer->answers->$questionId)
        <span class="color_primary">
        {{$answerType}} {{ $submittedAnswer->answers->$questionId }}
        </span>
        @else
          <span class="">
         {{ $submittedAnswer->answers->$questionId }}
        </span>
        @endif
      </span>
      @endif  
     
    </p>
    @endforeach
  </div>
</div>
