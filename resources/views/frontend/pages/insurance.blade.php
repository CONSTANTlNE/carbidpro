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

    <div class="container">


        <div class="ql-editor">
            {!! $insurance->text !!}
        </div>

    </div>

@endsection
