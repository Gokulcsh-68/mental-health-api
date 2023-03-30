


  @foreach($lists as $i => $element)
   

    @if(!empty($element->attributes->folio_values)) 
      @if(!empty($gender)) 
        @if(!empty($element->attributes->gender) ? $element->attributes->gender == strtolower($gender) : true) 

        <div style="border-bottom: 1px solid #ccc; padding: 5px">  
          <h4 class="color_primary">
            <b>{{ $element->name }}</b> - 
                     @foreach($element->attributes->folio_values as $vj => $evalue)
                           <span style="color: #000000">{{ $evalue }}, </span>
                        @endforeach
          </h4>

        
        </div>
        @endif
      @endif
    @endif

    @endforeach
