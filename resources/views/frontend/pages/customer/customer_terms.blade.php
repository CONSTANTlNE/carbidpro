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
            <div class="ft-faq-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="accordion flex-col justify-content-center align-middle" id="accordionExample">
                            <div class="accordion-item headline pera-content">
                                <h2 style="text-align: center!important;" class="accordion-header" id="headingOne">
                                    <button style="text-align: center; display: block; width: 100%;"
                                            class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        Insurance GE
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body ql-editor">
                                        {!! $insurance->text !!}
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item headline pera-content">
                                <h2 style="text-align: center!important;" class="accordion-header" id="headingOne2">
                                    <button style="text-align: center; display: block; width: 100%;"
                                            class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne2" aria-expanded="true"
                                            aria-controls="collapseOne2">
                                        My Fees
                                    </button>
                                </h2>
                                <div id="collapseOne2" class="accordion-collapse collapse" aria-labelledby="headingOne2"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body ">
                                        <div class="d-flex justify-content-center">
                                            @if($user->extra_expenses != null)
                                                <div class="d-flex flex-column mt-3  "
                                                     style="max-width: 200px;">
                                                    @php
                                                        $extras=json_decode($user->extra_expenses);
                                                    @endphp
                                                    @foreach($extras as $index => $extra)
                                                        @if($index>0)
                                                            <div class="d-flex justify-content-between align-items-middle mt-2">
                                                                <div class="d-flex justify-content-between align-middle"
                                                                     style="min-width:130px; margin-right: 10px ">
                                                                    <label style="cursor: pointer" class="mt-1"
                                                                           for="{{$extra->name}}">{{$extra->name}}</label>
                                                                    {{--                                <input style="cursor: pointer" class="form-check-input extra-checkbox"--}}
                                                                    {{--                                       id="{{$extra->name}}"--}}
                                                                    {{--                                       type="checkbox" value="{{$extra->value}}}">--}}
                                                                </div>
                                                                <input disabled class="form-control text-center"
                                                                       style="max-width: 150px"
                                                                       type="text"
                                                                       value="{{$extra->value}}">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item headline pera-content">
                                <h2 style="text-align: center!important;" class="accordion-header" id="headingOne3">
                                    <button style="text-align: center; display: block; width: 100%;"
                                            class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne3" aria-expanded="true"
                                            aria-controls="collapseOne3">
                                        Change Car owner information costs 150$
                                    </button>
                                </h2>
                                <div id="collapseOne3" class="accordion-collapse collapse" aria-labelledby="headingOne3"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body ">

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
