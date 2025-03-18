@extends('frontend.layout.app')

@php
    use App\Services\CreditService;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Session;
    use Carbon\Carbon    ;


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

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet"/>
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
    {{--PAYMENT AND NAV LINKS FOR DEALER--}}
    {{--    @include('frontend.components.customer-nav-links')--}}

    <div class="mt-5"></div>
    <section id="ft-faq-page" class="ft-faq-page-section page-padding">
        <div class="container">

            @if($user->extra_expenses != null)
                <h3 class="text-center">My Fees</h3>
                <div class="d-flex justify-content-center">
                    <div class="d-flex flex-column mt-3  "
                         style="max-width: 300px;">
                        @php
                            $extras=json_decode($user->extra_expenses);
                        @endphp
                        @foreach($extras as $index => $extra)
                            @if($index>0)
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="d-flex align-items-center" style="min-width: 130px; margin-right: 10px;">
                                        <label style="cursor: pointer; display: block; text-align: left; white-space: normal;" class="mt-1" for="{{$extra->name}}">
                                            {{$extra->name}}
                                        </label>
                                    </div>
                                    <input disabled class="form-control text-center" style="max-width: 150px; flex-shrink: 0;" type="text" value="{{$extra->value}}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-center mt-5">
                <ul style="max-width: 300px ; padding: 0">
                    <li>
                        <a style="color: blue" href="{{route('customer.insurance')}}">Insurance</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

@endsection
