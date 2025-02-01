@php
    use App\Services\CreditService;
     $creditService = new CreditService();
@endphp

@if($cars->isEmpty())
    <div class="d-flex justify-content-center">
        No Records
    </div>
@else
    <table style="width: 100%!important;">
        <thead>
        <tr style="text-align: center">
            <th> Make/Model/Year</th>
            <th>VIN</th>
            <th>Amount Due</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cars as $car)
            <tr
                    {{--For search on edit--}}
                    @if(isset($index))
                        onclick="document.getElementById('carName'+{{$index}}).value = '{{$car->make_model_year}}';
        document.getElementById('carID'+{{$index}}).value = {{$car->id}};
        document.getElementById('closeSearch2'+{{$index}}).click();
             "
                    {{--For search on creating new payment--}}
                    @else
                        onclick="document.getElementById('carName').value = '{{$car->make_model_year}}';
        document.getElementById('carID').value = {{$car->id}};
        document.getElementById('carID2').value = {{$car->id}};

        // for htmx call for calculating accrued percent and total amount due including accrued percent
    @if($car->latestCredit)
            document.getElementById('due').value = {{ round($car->latestCredit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))}}
            document.getElementById('due2').value = {{ round($car->latestCredit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))}}
     @else
        document.getElementById('due').value = {{$car->amount_due}};
        document.getElementById('due2').value = {{$car->amount_due}};
    @endif
        document.getElementById('closeSearch2').click();

        // eneable payment date only if car is found
        document.getElementById('payment_date').disabled=false
        document.getElementById('payment_date').setAttribute('min', '{{$car->created_at->format('Y-m-d')}}');

             "
                    @endif

                    style="text-align: center">
                <td style="cursor: pointer" class="mr-1 border">{{$car->make_model_year}}</td>
                <td style="cursor: pointer" class="mr-1 border">{{$car->vin}}</td>
                <td style="cursor: pointer" class="mr-1 border">

                    @if($car->latestCredit)
                        {{ round($car->latestCredit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))}}
                    @else
                        {{$car->amount_due }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
