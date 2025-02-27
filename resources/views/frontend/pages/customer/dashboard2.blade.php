@extends('frontend.layout.app')

@php
    use App\Services\CreditService;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Session;
    use Carbon\Carbon    ;

//@dd(auth()->user());
$creditService = new CreditService();

//             Cache::forget('dashboardStatics'.session()->get('locale'));

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
                    'My Deposit'=> $tr->translate('My Deposit'),
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
                    'Action'=> $tr->translate('Action'),
                ];
                Cache::forever('dashboardStatics'.session()->get('locale'), $data);
                $dashboardStatics=Cache::get('dashboardStatics'.session()->get('locale'));

            }

//          dd(auth()->user());
@endphp

{{--@dd(auth()->user());--}}

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
@endpush

@section('content')
    @push('style')
        <style>
            .container {
                max-width: 1700px;
            }

            /*table, th, td {*/
            /*    border: 1px solid;*/
            /*}*/

            td {
                padding: 7px;
            }


            .hideBr {
                display: none;
            }

            @media only screen and (max-width: 400px) {
                .hideBr {
                    display: block;
                }

                #bank_payment {
                    width: 250px !important;
                }

                #full_name {
                    width: 250px !important;
                }
            }

        </style>
    @endpush
{{--    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"--}}
{{--             data-background="/frontendAssets/images/cargo.jpeg"--}}
{{--             style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">--}}
{{--        <span class="background_overlay"></span>--}}
{{--        <div class="container">--}}
{{--            <div class="ft-breadcrumb-content headline text-center position-relative">--}}
{{--                <h2 style="margin-top: 80px;padding: 0;">--}}
{{--                    @if(request()->routeIs('customer.dashboard'))--}}
{{--                    {{$dashboardStatics['My Cars']}}--}}
{{--                    @elseif(request()->routeIs('customer.archivedcars'))--}}
{{--                        {{$dashboardStatics['Car History']}}--}}
{{--                    @endif--}}
{{--                </h2>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <div class="container">

        {{--PAYMENT AND NAV LINKS FOR DEALER--}}
        @include('frontend.components.customer-nav-links')

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <br>
                <div style="overflow-x: auto; width: 100%;">
                    <table id="myTable" class="display m-auto compact">
                        <thead>
                        <tr class="text-center">
                            <th >{{$dashboardStatics['Date']}}</th>
                            <th >{{$dashboardStatics['Make/Model/Year']}}</th>
                            <th >{{$dashboardStatics['VIN / CONTAINER']}}</th>
                            {{-- {{Cache::get('dashboardStatics'.session()->get('locale'))['']}} --}}
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th >{{$dashboardStatics['Release car to']}}</th>
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                @if (session()->get('auth')->child_of <= 0)
                                    <th >{{$dashboardStatics['Add Team']}}</th>
                                @else
                                    <th></th>
                                @endif
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th >{{$dashboardStatics['Payment']}}</th>
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th class="text-center">{{$dashboardStatics['Action']}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalAmountDue = 0;
                        @endphp

                        @foreach ($cars as $key => $car)
                            {{--Calculate Total Amount Due of all cars--}}
                            @if($car->latestCredit)
                                @php
                                    $totalAmountDue = $totalAmountDue +  round($car->latestCredit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))
                                @endphp
                            @else
                                @php
                                    $totalAmountDue = $totalAmountDue + round( $car->amount_due)
                                @endphp
                            @endif

                            @php
                                $createdDate = \Carbon\Carbon::parse($car->created_at);
                                $currentDate = \Carbon\Carbon::now();
                                $differenceInDays = $createdDate->diffInDays($currentDate);


                                    // amount due calclulation for car
                                      if($car->latestCredit){
                                       $amountDue= round($car->latestCredit->credit_amount+$creditService->totalInterestFromLastCalc($car->id));
                                        }else {
                                           $amountDue=  round($car->amount_due);
                                        }

                                      // tr Colors
                                      if ($amountDue<1 && $car->record_color!=='#F6CBCC'){
                                            $color = '#82f98261';
                                      } else {
                                          $color = $car->record_color;
                                      }
                            @endphp

                            <tr style="background-color: {{ $color }}">
                                <td style="font-size: 14px;min-width: 90px">
                                    {{ $car->created_at->format('d-m-Y') }}
                                    <span>Days:  {{ $car->created_at->diffInDays(now())}}</span>
                                </td>
                                <td >{{ $car->make_model_year }}</td>
                                {{-- Car VIN AND CONTAINER --}}
                                <td >
                                    <div class="d-flex flex-column  gap-2">
                                        <div class="d-flex justify-content-start align-items-middle gap-2">
                                            <span class="d-flex align-middle">{{$car->media->isNotEmpty() ? '📷' : ''}}</span>
                                            VIN:
                                            <a style="color: #1a7fca;font-size: 15px;" target="_blank"
                                               href="{{route('customer.car-info', $car->vin)}}">
                                                {{$car->vin}}
                                            </a>
                                            <span style="cursor: pointer;" class="p-cursor"
                                                  onclick="navigator.clipboard.writeText('{{ $car->vin }}')">
                                              <img width="30px" src="https://vsbrothers.com/img/copy_icon.svg">
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start align-items-middle gap-2">
                                            @if($car->groups->first()?->container_id )
                                                CONTAINER :
                                                <a style="color: #1a7fca;font-size: 15px; " target="_blank"
                                                   @if($car->groups->first()?->thc_agent==='MAERSK')
                                                       href="{{'https://www.maersk.com/tracking/' . $car->groups->first()?->container_id}}"
                                                   @endif
                                                   @if($car->groups->first()?->thc_agent==='Hapag-Eisa')
                                                       href="{{'https://www.hapag-lloyd.com/en/online-business/track/track-by-container-solution.html?container=' . $car->groups->first()?->container_id}}
                                                " {{$car->groups->first()?->container_id}}
                                                   @endif
                                                   @if($car->groups->first()?->thc_agent==='COSCO')
                                                       href="{{'https://elines.coscoshipping.com/ebusiness/cargoTracking?trackingType=BILLOFLADING&number=' . $car->groups->first()?->container_id}}
                                                " {{$car->groups->first()?->container_id}}
                                                   @endif
                                                   @if($car->groups->first()?->thc_agent==='Turkon-DTS')
                                                       href="{{'https://my.turkon.com/container-tracking'}}
                                                " {{$car->groups->first()?->container_id}}
                                                   @endif
                                                   @if($car->groups->first()?->thc_agent==='One net-Wilhelmsen')
                                                       href="{{'https://ecomm.one-line.com/one-ecom/manage-shipment/cargo-tracking' }}
                                                " {{$car->groups->first()?->container_id}}
                                                    @endif
                                                >{{$car->groups->first()?->container_id}}
                                                </a>
                                                <span style="cursor: pointer;" class="p-cursor"
                                                      onclick="navigator.clipboard.writeText('{{ $car->groups->first()->container_id}}')">
                                              <img width="30px" src="https://vsbrothers.com/img/copy_icon.svg">
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                {{-- Car Owner To--}}
                                @if (!auth()->user()->hasRole('portmanager'))
                                    <td  style="min-width: 160px">
                                        <div style="cursor: pointer" class="  d-flex align-items-start justify-content-center"
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
                                                <li class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_user">
                                                    <span id="vehicleOwnerNameError{{$key}}" style="color: red; display: none;"></span>
                                                    <input {{ $car->created_at->diffInDays(now())>5 ? 'readonly' : ''}} class="release_to form-control"
                                                           name="vehicle_owner_name"
                                                           placeholder="Full name"
                                                           required value="{{ $car->vehicle_owner_name }}"
                                                           {{--  accept only latin characters for full name--}}
                                                           oninput="
                                                             regex = /^[A-Za-z\s]*$/;
                                                             errorElement = document.getElementById('vehicleOwnerNameError{{$key}}');
                                                            // If the input doesn't match the allowed characters:
                                                            if (!regex.test(this.value)) {
                                                              errorElement.innerText = 'Only Latin characters';
                                                              errorElement.style.display = 'inline';
                                                              this.value = this.value.replace(/[^A-Za-z\s]+/g, '');
                                                            } else {
                                                              errorElement.innerText = '';
                                                              errorElement.style.display = 'none';
                                                            }
                                                        " >

                                                    <input {{ $car->created_at->diffInDays(now())>5 ? 'readonly' : ''}} type="hidden"
                                                           class="car_id" name="car_id"
                                                           required value="{{ $car->id }}">
                                                </li>

                                                <li class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_id">
                                                    <input {{ $car->created_at->diffInDays(now())>5 ? 'readonly' : ''}} class="id_number form-control"
                                                           name="owner_id_number"
                                                           required placeholder="ID Number"
                                                           value="{{ $car->owner_id_number }}">
                                                </li>

                                                <li class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_phone">
                                                    <input {{ $car->created_at->diffInDays(now())>5 ? 'readonly' : ''}} class="release_phone form-control"
                                                           name="owner_phone_number"
                                                           placeholder="Phone"
                                                           required value="{{ $car->owner_phone_number }}">
                                                </li>

                                                <li class="my-cars__td-relase-car-to-inputs-item">
                                                    <button
                                                            onclick="document.getElementById('release_to_{{ $car->id }}').style.display = 'none';"
                                                            type="button"
                                                            class="btn btn-primary save-release-information"
                                                            data-behavior="save-release-information">cancel
                                                    </button>
                                                    @if($car->created_at->diffInDays(now())<5)

                                                        <button class="btn btn-primary save-release-information"
                                                                data-behavior="save-release-information">Save
                                                        </button>
                                                    @endif
                                                </li>
                                            </form>
                                        </ul>
                                    </td>
                                @endif
                                {{-- Add Team--}}
                                @if (!auth()->user()->hasRole('portmanager'))
                                    @if (session()->get('auth')->child_of <= 0)
                                        <td style="width: 160px!important"   >
                                            <form style="width: 160px!important" action="{{route('customer.addTeamToCar')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="car_id" class="car_id"
                                                       value="{{ $car->id }}">
                                                <select style="max-width: 150px!important"   name="team_id" class="form-control team_id" id="team_id"
                                                        onchange="this.form.submit()">
                                                    <option value="remove_team"></option>
                                                    @foreach ($teams as $team)
                                                        <option  value="{{ $team->id }}"
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
                                {{-- Payments Column--}}
{{--                                @if (auth()->user()->child_of <= 0)--}}
                                    @if (!auth()->user()->hasRole('portmanager'))
                                        <td style="text-align: left">
                                            {{-- Total Cost incl interest if credit granted--}}
                                            <div style="font-size: 15px;width: 150px;margin-bottom: 2px ">
                                                <span class="mb-0">{{Cache::get('dashboardStatics'.session()->get('locale'))['Total Cost']}} :</span>
                                                @if($car->latestCredit)
                                                    @foreach($car->credit as $index3=> $credit)
                                                        @if($index3 === count($car->credit) - 1)
                                                            @if(Carbon::parse($credit->issue_or_payment_date)===Carbon::today())
                                                                <span style="color:blue">{{round($car->total_cost)}}</span>
                                                            @else
                                                                <span style="color:blue">{{ round($car->total_cost+$creditService->totalAccruedInterestTillToday($car->id))}}</span>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span style="color:blue">${{ $car->total_cost }}</span>
                                                @endif
                                            </div>
                                            {{-- Total Received on car--}}
                                            <div style="font-size: 15px;width: 150px;margin-bottom: 2px ">
                                                <span class="mb-0">{{Cache::get('dashboardStatics'.session()->get('locale'))['Received']}} :</span>
                                                <span style="color:green">${{ $car->payments->sum('amount')*-1 }}</span>
                                            </div>
                                            {{-- Amount Due on car--}}
                                            <div style="font-size: 15px;width: 150px ">
                                                <span class="mb-0">{{Cache::get('dashboardStatics'.session()->get('locale'))['Amount Due']}} :</span>
                                                <span style="color:red">${{$amountDue}}</span>
                                            </div>
                                        </td>
                                    @endif
{{--                                @endif--}}
                                {{-- Invoice payment and credit info--}}
                                @if (!auth()->user()->hasRole('portmanager'))
                                    <td style="min-width: 200px">
                                        <div style="display: flex;justify-content: space-around" class="mb-2">
                                            {{--Credit Info--}}
                                            @if($car->latestCredit)
                                                <button style="width: 67%!important;"
                                                        type="button" class="btn btn-primary btn-sm w-100"
                                                        data-toggle="modal"
                                                        data-target="#creditinfomodal{{$key}}">
                                                    {{$dashboardStatics['Credit Info']}}
                                                </button>
                                            @endif
                                            {{--Credit modal--}}
                                            @if($car->firstCredit)
                                                <div class="modal fade " id="creditinfomodal{{$key}}" tabindex="-1"
                                                     role="dialog"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content ">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Issue Date :
                                                                    {{$car->firstCredit->issue_or_payment_date->format('d-m-Y')}}
                                                                    :
                                                                    {{ $car->firstCredit->credit_amount }} $
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body p-0 "
                                                                 style="overflow-x: auto; width: 100%;">
                                                                <table style="margin: auto">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="p-1 text-center"
                                                                            style="min-width: 100px!important;">Date
                                                                        </th>
                                                                        <th class="p-1 text-center"
                                                                            style="width: 100px">
                                                                            Begin Amount due
                                                                        </th>
                                                                        <th class="p-1 text-center">Added Amount</th>
                                                                        <th class="p-1 text-center" style="width: 80px">
                                                                            Credit Days
                                                                        </th>
                                                                        <th class="p-1 text-center"
                                                                            style="width: 100px">
                                                                            Credit Fee
                                                                        </th>
                                                                        <th class="p-1 text-center"
                                                                            style="width: 100px">
                                                                            Total Amount
                                                                        </th>
                                                                        <th class="p-1 text-center" style="width: 60px">
                                                                            Paid Amount
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($car->credit as $index2=> $credit)
                                                                        <tr>
                                                                            @if($credit->added_amount!==null)
                                                                                <td class="p-1 text-center">{{$credit->issue_or_payment_date->format('d-m-Y')}}</td>
                                                                            @elseif($index2-1>=0)
                                                                                <td style="min-width: 100px"
                                                                                    class="p-1 text-center">{{$car->credit[$index2]->issue_or_payment_date->format('d-m-Y')}}</td>
                                                                            @endif
                                                                            @if($index2-1>=0)
                                                                                <td class="p-1 text-center">{{round($car->credit[$index2-1]->credit_amount)}}</td>
                                                                            @endif
                                                                            <td class="p-1 text-center"
                                                                                style="width:min-content!important">
                                                                                {{ $credit->comment? $credit->comment .'-': ''}}  {{ $credit->added_amount? $credit->added_amount . ' $ ':'' }}
                                                                            </td>
                                                                            <td class="p-1 text-center">{{$credit->credit_days}}</td>
                                                                            <td class="p-1 text-center">{{$credit->accrued_percent==null? '': round($credit->accrued_percent)}}</td>
                                                                            @if($index2-1>=0)
                                                                                <td class="p-1 text-center">{{round($car->credit[$index2-1]->credit_amount+$credit->accrued_percent+$credit->added_amount)}}</td>
                                                                            @endif
                                                                            <td class="p-1 text-center"
                                                                                style="width: 60px!important">{{$credit->paid_amount}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr style="background: #f2f2f2">
                                                                        <td class="p-1  text-center">{{Carbon::now()->format('d-m-Y')}}</td>
                                                                        <td class="p-1  text-center">{{round($credit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))}}</td>
                                                                        <td></td>
                                                                        <td class="p-1  text-center">{{$creditService->totalDaysFromLastCalcDate($car->id) }}</td>
                                                                        <td class="p-1  text-center">{{round($creditService->totalInterestFromLastCalc($car->id)) }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div class="d-flex justify-content-center gap-3 mt-3">
                                                                    <p>Amount Due Till
                                                                        Today: {{round($credit->credit_amount+round($creditService->totalInterestFromLastCalc($car->id)))}}</p>
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
                                            @endif
                                        </div>
                                        {{--Invoice--}}
                                        <form class="d-flex justify-content-center" target="_blank" action="{{route('customer.generate_invoice')}}"
                                              method="get">

                                            <input type="hidden" value="{{ $car->id }}" name="car_id">
                                            <button class="mb-1"
                                                    style="width: 67%; border: none;border-radius: 5px;background: #e2304e;color: white">
                                                {{$dashboardStatics['Invoice']}}
                                            </button>
                                        </form>
                                        {{--Payment--}}
                                        @if(!request()->routeIs('customer.archivedcars'))
                                            <div style="display: flex;justify-content: center;height: 30px">
                                                <form action="{{route('customer.set_amount')}}" method="post">
                                                    @csrf
                                                    <input  type="hidden" value="{{ $car->id }}" name="car_id">
                                                    <input style="width: 65px;height: 27px;border-radius: 5px;"
                                                           type="text"
                                                           name="amount">
                                                    <button style="width: 65px;border: none;border-radius: 5px;padding: 2px;background: #00b050;color: white">
                                                        {{$dashboardStatics['Pay']}}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            {{--                            @if($loop->last)--}}
                            {{--                                <div class="w-100 d-flex justify-content-center">--}}
                            {{--                                    <p class="btn btn-danger">Total Amount Due: {{$totalAmountDue}} $</p>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="w-100 d-flex justify-content-end">
                    <p class="btn btn-danger">Total Amount Due: {{$totalAmountDue}} $</p>
                </div>
                <br>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
        <script>
            let table = new DataTable('#myTable', {
                order: [[0, 'desc']],
                pageLength: 50
            });

            document.getElementById('myTable_wrapper').children[0].style.width = '100%';
            let text = document.querySelector('[for="dt-length-0"]');
            text.style.display = 'none';
        </script>
    @endpush
@endsection
