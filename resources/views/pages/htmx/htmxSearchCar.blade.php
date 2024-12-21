

@if($cars->isEmpty())
    <div class="d-flex justify-content-center">
        No Records
    </div>
@else
    <table style="width: 100%!important;">
        <thead>
        <tr style="text-align: center">
            <th> Make/Model/Year </th>
            <th>VIN</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cars as $car)
            <tr
                    @if(isset($index))
                        onclick="document.getElementById('carName'+{{$index}}).value = '{{$car->make_model_year}}';
        document.getElementById('carID'+{{$index}}).value = {{$car->id}};
        document.getElementById('carVIN'+{{$index}}).value = {{$car->vin}};
        document.getElementById('closeSearch2'+{{$index}}).click();
             "
                    @else
                        onclick="document.getElementById('carName').value = '{{$car->make_model_year}}';
        document.getElementById('carID').value = {{$car->id}};
        document.getElementById('carVIN').value = {{$car->vin}};
        document.getElementById('closeSearch2').click();
             "
                    @endif

                style="text-align: center">
                <td  style="cursor: pointer" class="mr-1 border">{{$car->make_model_year}}</td>
                <td  style="cursor: pointer" class="mr-1 border">{{$car->vin}}</td>
                <td  style="cursor: pointer" class="mr-1 border">{{$car->amount_due}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif