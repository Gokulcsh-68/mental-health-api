


  @foreach($lists as $i => $element)

  <div style="border-bottom: 1px solid #ccc; padding: 5px">
      <h4 class="color_primary">
        <b>{{ $element->name }}</b>
      </h4>



      @foreach($element->type as $j => $etype)
          <h5 >
            <b class="color_secondary">{{ $etype->name }}</b>

          </h5>
            @foreach($etype->sub_type as $k => $esubtype)

                @if(!empty($esubtype->attributes->folio_values))

                  <h5>
                    <b>{{ $esubtype->name }}</b>
                  </h5>

                    @foreach($esubtype->attributes->folio_values as $vj => $evalue)
                       <span style="color: #007bff">{{ $evalue }}, </span>
                    @endforeach
                 
                @endif

              @endforeach
        @endforeach
    </div>

    @endforeach
