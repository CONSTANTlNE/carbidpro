@extends('frontend.layout.app')

@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg"
        style="background-image: url(&quot;https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 50px;">{!! $tr->translate('Contact') !!}</h2>

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
                                        <h3>{!! $tr->translate('Office address') !!}</h3>
                                        {!! $tr->translate($settings->get('office')) !!}
                                    </div>
                                </div>
                                <div class="ft-contact-cta-items d-flex">
                                    <div class="ft-contact-cta-icon d-flex align-items-center justify-content-center">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="ft-contact-cta-text headline pera-content">
                                        <h3>{!! $tr->translate('Telephone number') !!}</h3>
                                        {!! $tr->translate($settings->get('phone')) !!}
                                    </div>
                                </div>
                                <div class="ft-contact-cta-items d-flex">
                                    <div class="ft-contact-cta-icon d-flex align-items-center justify-content-center">
                                        <i class="far fa-envelope"></i>
                                    </div>
                                    <div class="ft-contact-cta-text headline pera-content">
                                        <h3>{!! $tr->translate('Mail address') !!}</h3>
                                        {!! $tr->translate($settings->get('mail')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ft-contact-page-form-wrapper headline">
                            <h3 class="text-center">{!! $tr->translate('Contact us') !!}</h3>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form action="{{ route('sendEmail') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" name="fname" required placeholder="{!! $tr->translate('Name') !!}">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" name="lname" placeholder="{!! $tr->translate('Surname') !!}">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="email" required name="email"
                                            placeholder="{!! $tr->translate('Email') !!}">
                                    </div>

                                    <div class="col-lg-6">
                                        <input type="tel" name="phone" required
                                            placeholder="{!! $tr->translate('Your Phone') !!}">
                                    </div>

                                    <div class="col-lg-12">
                                        <textarea name="message" placeholder="{!! $tr->translate('Your Message') !!}"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <style>
                                            .form-group {
                                                display: flex;
                                            }
        
                                            .form-group>input {
                                                height: 20px;
                                        margin-top: 12px;
                                        width: 10%;
                                            }
                                        </style>
                                        <strong> SMS Consent</strong>
                                        <div class="form-group">
                                            <input type="checkbox" id="sms" name="sms" />
                                            <label for="sms">By submitting this form, you agree to
                                                recive SMS message from our
                                                company regarding our services.</label>
                                        </div>

                                    </div>
                                    <div class="col-lg-12">
                                        <button> {!! $tr->translate('Send') !!}</button>
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
