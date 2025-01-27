@php
    $customerextraexpense = json_decode($selectedcustomer->extra_expenses, true);
@endphp


    @foreach($extra_expenses as $index10 => $extraexpence)
        <div class="col-md-2 form-group">
            <label class="control-label">{{$extraexpence->name}}</label>

            <input name="{{$extraexpence->name}}"
                   type="text"
                   placeholder=""
                   class="form-control"
                   @if($selectedcustomer->extra_expenses != null)
                       value="{{array_key_exists($extraexpence->name,$customerextraexpense)?$customerextraexpense[$extraexpence->name] : '' }}"
                    @endif
            >
        </div>
    @endforeach

