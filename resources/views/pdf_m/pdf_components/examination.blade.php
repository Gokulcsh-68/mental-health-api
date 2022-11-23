


  @foreach($lists as $i => $element)
   

    @if(!empty($element->attributes->folio_values)) 
      @if(!empty($gender)) 
        @if(!empty($element->attributes->gender) ? $element->attributes->gender == strtolower($gender) : true) 

        <div style="border-bottom: 1px solid #ccc; padding: 5px">  
          <h4 class="color_primary">
            <b>{{ $element->name }}</b>
          </h4>

         @foreach($element->attributes->folio_values as $vj => $evalue)
                           <span style="color: #007bff">{{ $evalue }}, </span>
                        @endforeach
        </div>
        @endif
      @endif
    @endif

    @endforeach
