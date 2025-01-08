@extends('frontend.layout.app')

@php

    $contactStatics=Cache::get('contactStatics'.session()->get('locale'));

              if($contactStatics===null){

                  $data=[
                      'Contact'=>$tr->translate('Contact'),
                      'Office address'=>$tr->translate('Office address'),
                      'Telephone number'=>$tr->translate('Telephone number'),
                      'Mail address'=>$tr->translate('Mail address'),
                      'Contact us'=>$tr->translate('Contact us'),
                      'Name'=>$tr->translate('Name'),
                      'Surname'=>$tr->translate('Surname'),
                      'Email'=>$tr->translate('Email'),
                      'Your Phone'=>$tr->translate('Your Phone'),
                      'Your Message'=>$tr->translate('Your Message'),
                      'Send'=>$tr->translate('Send'),
                      'Consent'=>$tr->translate('Consent'),
                      'consent'=>$tr->translate('By submitting this form, you agree to recive SMS message from our company regarding our services.'),

                  ];

                  Cache::forever('contactStatics'.session()->get('locale'), $data);
                  $contactStatics=Cache::get('contactStatics'.session()->get('locale'));


              }
//
//                  Cache::forget('contactStaticsen');
//                  Cache::forget('contactStaticsru');

@endphp

@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
             data-background="https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg"
             style="background-image: url(&quot;https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 50px;">{{ $contactStatics['Contact'] }} </h2>
            </div>
        </div>
    </section>
    <section id="ft-contact-page" class="ft-contact-page-section page-padding">
        <div class="container">
            <div class="ft-contact-page-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="ft-contact-page-text-wrapper">

                            <div class="ft-contact-page-contact-info">
                                <div class="ft-contact-cta-items d-flex">
                                    <div class="ft-contact-cta-icon d-flex align-items-center justify-content-center">
                                        <i class="fal fa-map-marker-alt"></i>
                                    </div>
                                    <div class="ft-contact-cta-text headline pera-content">
                                        <h3>{{ $contactStatics['Office address'] }} </h3>
                                        {!! $settings->where('key', 'address')->first()->value !!}
                                    </div>
                                </div>
                                <div class="ft-contact-cta-items d-flex">
                                    <div class="ft-contact-cta-icon d-flex align-items-center justify-content-center">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="ft-contact-cta-text headline pera-content">
                                        <h3>
                                            {{ $contactStatics['Telephone number'] }}
                                        </h3>
                                        {!! $settings->where('key', 'phone')->first()->value !!}

                                    </div>
                                </div>
                                <div class="ft-contact-cta-items d-flex">
                                    <div class="ft-contact-cta-icon d-flex align-items-center justify-content-center">
                                        <i class="far fa-envelope"></i>
                                    </div>
                                    <div class="ft-contact-cta-text headline pera-content">
                                        <h3>
                                            {{ $contactStatics['Mail address'] }}

                                        </h3>
                                        {!! $settings->where('key', 'email')->first()->value !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ft-contact-page-form-wrapper headline">
                            <h3 class="text-center">
                                {{ $contactStatics['Contact us'] }}
                            </h3>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form action="{{ route('sendEmail') }}" method="POST">
                                @csrf
                                <input type="hidden" name="honeypot">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" name="fname" required
                                               placeholder="  {{ $contactStatics['Name'] }}">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" name="lname"
                                               placeholder="  {{ $contactStatics['Surname'] }}">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="email" required name="email"
                                               placeholder="  {{ $contactStatics['Email'] }}">
                                    </div>

                                    <div class="col-lg-6">
                                        <input type="tel" name="phone" required
                                               placeholder="  {{$contactStatics['Your Phone'] }}">
                                    </div>

                                    <div class="col-lg-12">
                                        <textarea name="message"
                                                  placeholder="  {{ $contactStatics['Your Message'] }}"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <style>
                                            .form-group {
                                                display: flex;
                                            }

                                            .form-group > input {
                                                height: 20px;
                                                margin-top: 12px;
                                                width: 10%;
                                            }
                                        </style>
                                        <strong>
                                            SMS {{ $contactStatics['Consent'] }}</strong>
                                        <div class="form-group">
                                            <input type="checkbox" id="sms" name="sms"/>
                                            <label for="sms">
                                                {{ $contactStatics['consent'] }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button>{{ $contactStatics['Send'] }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
