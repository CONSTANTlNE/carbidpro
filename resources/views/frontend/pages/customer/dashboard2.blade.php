@extends('frontend.layout.app')

@php
    use App\Services\CreditService;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Session;
    use Carbon\Carbon    ;

$creditService = new CreditService();

//     Cache::forget('dashboardStatics'.session()->get('locale'));
           $dashboardStatics=Cache::get('dashboardStatics'.session()->get('locale'));




          if($dashboardStatics===null){

                $data=[
                    'My Cars'=> $tr->translate('My Cars'),
                    'Car History'=> $tr->translate('Car History'),
                    'Payment History'=> $tr->translate('Payment History'),
                    'Transferred Amount'=> $tr->translate('Transferred Amount'),
                    'Sender'=> $tr->translate('Sender'),
                    'Full Name'=> $tr->translate('Full Name'),
                    'Submit'=> $tr->translate('Submit'),
                    'Deposit'=> $tr->translate('Deposit'),
                    'Search placeholder'=> $tr->translate('Search by VIN or Container'),
                    'payment_confirmation'=> $tr->translate('Payment  will be confirmed within 24 hours.'),
                    'Car list'=> $tr->translate('Car list'),

                    'Date'=> $tr->translate('Date'),
                    'Make/Model/Year'=> $tr->translate('Make/Model/Year'),
                    'VIN / CONTAINER'=> $tr->translate('VIN / CONTAINER'),
                    'Release car to'=> $tr->translate('Release car to'),
                    'Add Team'=> $tr->translate('Add Team'),
                    'Payment'=> $tr->translate('Payment'),
                    'Total Cost'=> $tr->translate('Total Cost'),
                    'Received'=> $tr->translate('Received'),
                    'Amount Due'=> $tr->translate('Amount Due'),
                    'Credit Info'=> $tr->translate('Credit Info'),
                    'Invoice'=> $tr->translate('Invoice'),
                    'Pay'=> $tr->translate('Pay'),
                ];
                Cache::forever('dashboardStatics'.session()->get('locale'), $data);
           $dashboardStatics=Cache::get('dashboardStatics'.session()->get('locale'));

            }

@endphp

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
@endpush

@section('content')
    @push('style')
        <style>
            .container {
                max-width: 1700px;
            }

            table, th, td {
                border: 1px solid;
            }

            td {
                padding: 7px;
            }
        </style>
    @endpush
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
             data-background="/frontendAssets/images/cargo.jpeg"
             style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">
                    {{$dashboardStatics['Car list']}}
                </h2>
            </div>
        </div>
    </section>

    <div class="container">
        @include('frontend.components.customer-nav-links')


        <div class="row justify-content-center">
            <div class="col-lg-8">
                <br>
                {{--                <div class="d-flex justify-content-center mb-3">--}}
                {{--                    <input class="text-center" style="width: 250px" type="text" name="search" id="search"--}}
                {{--                           placeholder="{{Cache::get('dashboardStatics'.session()->get('locale'))['Search placeholder']}}">--}}
                {{--                </div>--}}
                <div style="overflow-x: auto; width: 100%;">
                    <table id="myTable" class="display m-auto">
                        <thead>
                        <tr class="text-center">
                            <th class="text-center">{{$dashboardStatics['Date']}}</th>
                            <th class="text-center">{{$dashboardStatics['Make/Model/Year']}}</th>
                            <th class="text-center">{{$dashboardStatics['VIN / CONTAINER']}}</th>
                            {{-- {{Cache::get('dashboardStatics'.session()->get('locale'))['']}} --}}
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th class="text-center">{{$dashboardStatics['Release car to']}}</th>
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                @if (session()->get('auth')->parent_of <= 0)
                                    <th class="text-center">{{$dashboardStatics['Add Team']}}</th>
                                @else
                                    <th></th>
                                @endif
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th class="text-center">{{$dashboardStatics['Payment']}}</th>
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cars as $key => $car)
                            @php
                                $createdDate = \Carbon\Carbon::parse($car->created_at);
                                $currentDate = \Carbon\Carbon::now();
                                $differenceInDays = $createdDate->diffInDays($currentDate);

                                if ((isset($balance) && $balance <= 0) || $car->total_cost <= 0) {
                                    $color = '#82f98261';
                                } else {
                                    $color = $car->color;
                                }
                            @endphp
                            <tr style="background-color: {{ $color }}" class="text-center">
                                <td style="font-size: 12px;min-width: 90px"
                                    class="text-center">{{ $car->created_at->format('d-m-Y') }}</td>
                                <td class="text-center">{{ $car->make_model_year }}</td>
                                <td class="text-center">
                                    <a style="color: #1a7fca;font-size: 15px;" target="_blank"
                                       href="{{route('customer.car-info', $car->vin)}}"> {!! $car->getMedia()->first() ? '📷 ' . $car->vin : $car->vin !!}
                                    </a>
                                    <span style="cursor: pointer;" class="p-cursor"
                                          onclick="navigator.clipboard.writeText('{{ $car->vin }}')">
                                    <img width="20px" src="https://vsbrothers.com/img/copy_icon.svg">
                                </span>
                                </td>
                                {{-- Car Owner To--}}
                                @if (!auth()->user()->hasRole('portmanager'))
                                    <td class="text-center" style="min-width: 160px">
                                        <div  class="my-cars__td-relase-car-to-item  d-flex align-items-center justify-content-center"
                                             onclick="
                                    tglbtn= document.getElementById('release_to_{{ $car->id }}');
                                    if(tglbtn.style.display == 'none'){
                                        tglbtn.style.display = 'block';
                                    }else{
                                        tglbtn.style.display = 'none';
                                    }
                                    ">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24">
                                                <g fill="none" stroke="#1c9b2e" stroke-linecap="round"
                                                   stroke-linejoin="round" stroke-width="2">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </g>
                                            </svg>
                                            {{ $car->vehicle_owner_name }}
                                        </div>
                                        <ul id="release_to_{{ $car->id }}" class="my-cars__td-relase-car-to-inputs"
                                            style="display: none;">
                                            <form action="{{route('saveRelease')}}" method="post">
                                                @csrf
                                                <li
                                                        class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_user">
                                                    <input class="release_to form-control" name="vehicle_owner_name"
                                                           placeholder="Full name"
                                                           value="{{ $car->vehicle_owner_name }}">
                                                    <input type="hidden" class="car_id" name="car_id"
                                                           value="{{ $car->id }}">
                                                </li>

                                                <li
                                                        class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_id">
                                                    <input class="id_number form-control" name="owner_id_number"
                                                           placeholder="ID Number" value="{{ $car->owner_id_number }}">
                                                </li>

                                                <li
                                                        class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_phone">
                                                    <input class="release_phone form-control" name="owner_phone_number"
                                                           placeholder="Phone"
                                                           value="{{ $car->owner_phone_number }}">
                                                </li>
                                                <li class="my-cars__td-relase-car-to-inputs-item">
                                                    <button class="btn btn-primary save-release-information"
                                                            data-behavior="save-release-information">Save
                                                    </button>
                                                </li>
                                            </form>
                                        </ul>
                                    </td>
                                @endif
                                {{-- Add Team--}}
                                @if (!auth()->user()->hasRole('portmanager'))
                                    @if (session()->get('auth')->parent_of <= 0)
                                        <td style="min-width: 150px" class="text-center">
                                            <form action="{{route('customer.addTeamToCar')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="car_id" class="car_id"
                                                       value="{{ $car->id }}">
                                                <select name="team_id" class="form-control team_id" id="team_id"
                                                        onchange="this.form.submit()">
                                                    <option value="remove_team"></option>
                                                    @foreach ($teams as $team)
                                                        <option value="{{ $team->id }}"
                                                                {{ $car->team_id == $team->id ? 'selected' : '' }}>
                                                            {{ $team->contact_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @endif
                                {{-- Payment--}}
                                @if (session()->get('auth')->parent_of <= 0)
                                    @if (!auth()->user()->hasRole('portmanager'))
                                        <td style="text-align: left">
                                            <div style="font-size: 15px;width: 150px;margin-bottom: 10px ">
                                                <span class="mb-0">{{Cache::get('dashboardStatics'.session()->get('locale'))['Total Cost']}} :</span>
                                                <span style="color:blue">${{ $car->total_cost }}</span>
                                            </div>
                                            <div style="font-size: 15px;width: 150px;margin-bottom: 10px ">
                                                <span class="mb-0">{{Cache::get('dashboardStatics'.session()->get('locale'))['Received']}} :</span>
                                                <span style="color:green">${{ $car->payments->sum('amount')*-1 }}</span>
                                            </div>
                                            <div style="font-size: 15px;width: 150px ">
                                                <span class="mb-0">{{Cache::get('dashboardStatics'.session()->get('locale'))['Amount Due']}} :</span>
                                                @if($car->latestCredit)
                                                    @foreach($car->credit as $index3=> $credit)

                                                        @if($index3 === count($car->credit) - 1)
                                                            @if(Carbon::parse($credit->issue_or_payment_date)==Carbon::today())
                                                                <span style="color: red ">{{round($credit->credit_amount)}}</span>
                                                            @else
                                                                <span style="color: red ">{{ round($credit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))}}</span>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span style="color:red">${{round( $car->amount_due) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                @endif
                                {{-- Invoice--}}
                                @if (!auth()->user()->hasRole('portmanager'))
                                    <td style="min-width: 200px">
                                        <div style="display: flex;justify-content: space-around" class="mb-2">
                                            {{--Credit Info--}}
                                            @if($car->latestCredit)
                                                <button type="button" class="btn btn-primary btn-sm w-100"
                                                        data-toggle="modal"
                                                        data-target="#creditinfomodal{{$key}}">
                                                    {{$dashboardStatics['Credit Info']}}
                                                </button>
                                            @endif
                                            {{--Credit modal--}}
                                            <div class="modal fade " id="creditinfomodal{{$key}}" tabindex="-1"
                                                 role="dialog"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content p-1 text-center">
                                                        <div class="modal-header justify-content-center">
                                                            <h5 class="modal-title">{{$dashboardStatics['Credit Info']}}</h5>
                                                        </div>
                                                        <div class="modal-body p-0 d-flex justify-content-center">
                                                            <div style="overflow-x: auto; width: 100%;">
                                                                <table style="margin: auto">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="p-1 text-center"
                                                                            style="min-width: 100px">Date
                                                                        </th>
                                                                        <th class="p-1 text-center"
                                                                            style="width: 100px">
                                                                            Begin Amount due
                                                                        <th class="p-1 text-center">Added Amount</th>
                                                                        <th class="p-1 text-center" style="width: 80px">
                                                                            Credit
                                                                            Days
                                                                        </th>
                                                                        <th class="p-1 text-center"
                                                                            style="width: 100px">Credit
                                                                            Fee
                                                                        </th>
                                                                        <th class="p-1 text-center"
                                                                            style="width: 100px">Total
                                                                            Amount
                                                                        </th>
                                                                        <th class="p-1 text-center" style="width: 60px">
                                                                            Paid
                                                                            Amount
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($car->credit as $index2=> $credit)
                                                                        <tr>
                                                                            @if($credit->added_amount!==null)
                                                                                <td class="p-1">{{$credit->issue_or_payment_date->format('d-m-Y')}}</td>
                                                                            @elseif($index2-1>=0)
                                                                                <td style="min-width: 100px"
                                                                                    class="p-1">{{$car->credit[$index2]->issue_or_payment_date->format('d-m-Y')}}</td>
                                                                            @endif
                                                                            @if($index2-1>=0)
                                                                                <td class="p-1">{{round($car->credit[$index2-1]->credit_amount)}}</td>
                                                                            @endif
                                                                            <td class="p-1" style="width: 60px!important">
                                                                                {{ $credit->added_amount}}
                                                                            </td>
                                                                            <td class="p-1">{{$credit->credit_days}}</td>
                                                                            <td class="p-1">{{$credit->accrued_percent==null? '': round($credit->accrued_percent)}}</td>
                                                                            @if($index2-1>=0)
                                                                                <td class="p-1">{{round($car->credit[$index2-1]->credit_amount+$credit->accrued_percent+$credit->added_amount)}}</td>
                                                                            @endif
                                                                            <td class="p-1"
                                                                                style="width: 60px!important">{{$credit->paid_amount}}</td>
                                                                        </tr>
                                                                    @endforeach

                                                                    <tr style="background: #f2f2f2">
                                                                        <td class="p-1">{{Carbon::now()->format('d-m-Y')}}</td>
                                                                        <td class="p-1">{{round( $credit->credit_amount+round($creditService->totalInterestFromLastCalc($car->id)))}}</td>
                                                                        <td></td>
                                                                        <td> {{$creditService->totalDaysFromLastCalcDate($car->id) }}</td>
                                                                        <td> {{round($creditService->totalInterestFromLastCalc($car->id,)) }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <a>
                                            <button class="mb-1"
                                                    style="width: 100%; border: none;border-radius: 5px;background: #e2304e;color: white">
                                                {{Cache::get('dashboardStatics'.session()->get('locale'))['Invoice']}}
                                            </button>
                                        </a>
                                        <div style="display: flex;justify-content: center;height: 30px">

                                                <form action="{{route('customer.set_amount')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $car->id }}" name="car_id">
                                                    <input style="width: 80px;height: 100%;border-radius: 5px;"
                                                           type="number"
                                                           name="amount">
                                                    <button style="width: 80px;border: none;border-radius: 5px;padding: 2px;background: #00b050;color: white">
                                                        {{Cache::get('dashboardStatics'.session()->get('locale'))['Pay']}}
                                                    </button>
                                                </form>

                                        </div>
                                    </td>
                                @endif
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{ $key }}" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Send Invoice</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('customer.addInvoicePrice') }}" method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $car->id }}" name="car_id">

                                                <div class="form-group">
                                                    <label for="">All Cost</label>
                                                    <input type="number" class="form-control" name="invoice_debit"
                                                           value="">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Recived</label>

                                                    <input type="number" class="form-control" name="invoice_received"
                                                           value="">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Amount due</label>
                                                    <input type="number" class="form-control" name="invoice_balance"
                                                           value="">
                                                </div>

                                                <button type="submit" class="btn btn-success mt-2">Send</button>

                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <br>

            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
        <script>
            let table = new DataTable('#myTable');

            document.getElementById('myTable_wrapper').children[0].style.width = '100%';
            let text = document.querySelector('[for="dt-length-0"]');
            text.style.display='none';
        </script>
    @endpush
@endsection
