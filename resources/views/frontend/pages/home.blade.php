@extends('frontend.layout.app')

@section('content')


    @if (count($slides) >= 0)
        <!-- Start of Slider section
                                                                                                                                                 ============================================= -->
        <rs-module-wrap id="rev_slider_27_1_wrapper" data-alias="slider-7" data-source="gallery"
            style="visibility:hidden;background:transparent;padding:0;margin:0px auto;margin-top:0;margin-bottom:0;">
            <rs-module id="rev_slider_27_1" style="" data-version="6.5.8">
                <rs-slides>
                    @foreach ($slides as $key => $slider)
                        <rs-slide style="position: absolute;" data-key="rs-{{ $key }}" data-title="Slide"
                            data-thumb="{{ Storage::url($slider->image) }}" data-in="o:0;" data-out="a:false;">
                            <img src="{{ Storage::url($slider->image) }}" alt="Image" title="slider-1" width="1614"
                                height="908" class="rev-slidebg tp-rs-img" data-parallax="3"
                                data-panzoom="d:10000;ss:100;se:120%;" data-no-retina>
                            <rs-layer id="slider-27-slide-70-layer-0" data-type="image" data-rsp_ch="on"
                                data-text="w:normal;s:20,16,12,7;l:0,20,15,9;"
                                data-dim="w:['100%','100%','100%','100%'];h:['100%','100%','100%','100%'];"
                                data-basealign="slide" data-frame_999="o:0;st:w;" style="z-index:8;"><img
                                    src="frontendAssets/slider-shape1.png" alt="" class="tp-rs-img" width="1903"
                                    height="901" data-c="cover-proportional" data-no-retina>
                            </rs-layer>
                            <rs-layer class="slider_title" id="slider-27-slide-70-layer-title-{{ $key }}"
                                data-type="text" data-rsp_ch="on" data-xy="xo:15px,12px,9px,5px;yo:340px,280px,212px,124px;"
                                data-text="w:normal;s:72,59,44,40;l:80,66,50,40;fw:700;" data-frame_0="o:1;"
                                data-frame_0_chars="d:5;o:0;rX:-90deg;oZ:-50;" data-frame_1="sp:1750;"
                                data-frame_1_chars="e:power4.inOut;d:10;oZ:-50;" data-frame_999="o:0;st:w;"
                                style="z-index:9;font-family:'Poppins';">
                                {!! $tr->translate($slider->title) !!}
                            </rs-layer>
                            <rs-layer id="slider-27-slide-70-layer-2" data-type="text" data-rsp_ch="on"
                                data-xy="xo:15px,12px,9px,5px;yo:535px,445px,338px,219px;"
                                data-text="w:normal;s:18,14,16,16;l:25,20,20,20;fw:500;" data-frame_0="x:-100%;"
                                data-frame_0_mask="u:t;" data-frame_1="e:power4.inOut;st:1000;sp:1300;"
                                data-frame_1_mask="u:t;" data-frame_999="o:0;st:w;"
                                style="z-index:10;font-family:'Roboto';">
                            </rs-layer>
                            <a id="slider-27-slide-70-layer-5" class="rs-layer rev-btn" href="/about" target="_blank"
                                rel="noopener" data-type="button" data-rsp_ch="on"
                                data-xy="xo:235px,194px,147px,138px;yo:620px,512px,389px,275px;"
                                data-text="w:normal;s:18,14,10,14;l:52,42,31,40;fw:500;a:center;"
                                data-dim="w:200px,165px,125px,120px;h:55px,45px,34px,45px;minh:0px,none,none,none;"
                                data-padding="r:40,33,25,15;l:40,33,25,15;"
                                data-border="bos:solid;boc:#ffffff;bow:2px,2px,2px,2px;bor:3px,3px,3px,3px;"
                                data-frame_0="x:175%;o:1;" data-frame_0_mask="u:t;x:-100%;"
                                data-frame_1="e:power3.out;st:1500;sp:1000;" data-frame_1_mask="u:t;"
                                data-frame_999="o:0;st:w;"
                                data-frame_hover="bgc:#ea1e00;boc:#ea1e00;bor:3px,3px,3px,3px;bos:solid;bow:2px,2px,2px,2px;sp:100;e:power1.inOut;bri:120%;"
                                style="z-index:12;background-color:#00044b;font-family:'Poppins';">About Us
                            </a>
                            <a id="slider-27-slide-70-layer-6" class="rs-layer rev-btn" href="#ft-service" rel="noopener"
                                data-type="button" data-rsp_ch="on"
                                data-xy="xo:15px,12px,9px,5px;yo:620px,512px,389px,274px;"
                                data-text="w:normal;s:18,14,10,14;l:52,42,31,40;fw:500;a:center;"
                                data-dim="w:200px,165px,125px,120px;h:55px,45px,34px,45px;minh:0px,none,none,none;"
                                data-padding="r:40,33,25,15;l:40,33,25,15;"
                                data-border="bos:solid;boc:#ffffff;bow:2px,2px,2px,2px;bor:3px,3px,3px,3px;"
                                data-frame_0="x:-175%;o:1;" data-frame_0_mask="u:t;x:100%;"
                                data-frame_1="e:power3.out;st:1200;sp:1000;" data-frame_1_mask="u:t;"
                                data-frame_999="o:0;st:w;"
                                data-frame_hover="bgc:#ea1e00;boc:#ea1e00;bor:3px,3px,3px,3px;bos:solid;bow:2px,2px,2px,2px;sp:100;e:power1.inOut;bri:120%;"
                                style="z-index:11;background-color:#00044b;font-family:'Poppins';">Our Service
                            </a>
                        </rs-slide>
                    @endforeach
                </rs-slides>

            </rs-module>
        </rs-module-wrap>
        <!-- End of Slider section
                                                                                                                                                 ============================================= -->
    @endif

    <!-- Start of Booking form section
                                                                                                                                                 ============================================= -->
    <section id="ft-booking-form" class="ft-booking-form-section">
        <div class="container">
            <div class="ft-booking-form-content position-relative">
                <form action="{{ route('customer.searchResult') }}" method="GET">

                    <div class="booking-form-input-wrapper d-flex flex-wrap justify-content-center">
                        <label class="booking-form-input position-relative">
                            <span class="booking-form-icon"><i class="flaticon-face-detection"></i></span>
                            <input type="text" minlength="5" name="search" placeholder="{!! $tr->translate('Search by Container or VIN Code') !!}">
                        </label>

                        <button class="ft-sb-button" type="submit">{!! $tr->translate('Search') !!}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- End of Booking form section
                                                                                                                                                 ============================================= -->


    <!-- Start of Service section
                                                                                                                                                 ============================================= -->
    <section id="ft-service" class="ft-service-section">
        <div class="container">
            <div class="ft-service-content">
                <div class="row">
                    <div class="col-lg-3 wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                        <div class="ft-service-text-area">
                            <div class="ft-section-title headline pera-content">
                                <h2>{!! $tr->translate('Our Services') !!}</h2>
                            </div>
                            <div class="ft-btn">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="ft-service-slider-area">
                            <div class="ft-service-slider-wrapper">
                                @foreach ($services as $service)
                                    <div class="ft-item-innerbox wow fadeInLeft" data-wow-delay="200ms"
                                        data-wow-duration="1500ms">
                                        <div class="ft-service-slider-item">
                                            <div class="ft-service-inner-img">
                                                <img src="{{ Storage::url($service->image) }}"
                                                    alt="{{ $service->title }}">
                                            </div>
                                            <div class="ft-service-inner-text headline pera-content position-relative">
                                                <h3><a href="{{ $service->button_url }}">{!! $tr->translate($service->title) !!}</a></h3>

                                                <a class="service-more"
                                                    href="{{ $service->button_url }}">{!! $tr->translate($service->button_title) !!}
                                                    <span>+</span></a>
                                                <div class="ft-service-serial position-absolute">
                                                    1
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of Service section
                                                                                                                                                 ============================================= -->


    <!-- Start of Contact section
                                                                                                                                                 ============================================= -->
    <section id="ft-contact" class="ft-contact-section position-relative" data-background="frontendAssets/images/cargo.jpeg">
        <div class="container">
            <div class="ft-contact-content">
                <div class="ft-section-title headline pera-content">
                    <span class="sub-title">{!! $tr->translate('Contact Us') !!}</span>
                    <h2> {!! $tr->translate('Request') !!}</h2>
                </div>
                <div class="ft-contact-form-wrapper">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('sendEmail') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" required name="fname" placeholder="{!! $tr->translate('Name') !!}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="lname" placeholder="{!! $tr->translate('Surname') !!}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" required name="email" placeholder="{!! $tr->translate('Email') !!}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" required name="phone" placeholder="{!! $tr->translate('Phone') !!}">
                            </div>
                            <div class="col-md-12">
                                <textarea name="message" placeholder="{!! $tr->translate('Your Message') !!}"></textarea>
                            </div>
                            <div class="col-md-12">
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
                            <div class="col-md-12">
                                <button class="ft-sb-button">{!! $tr->translate('Send') !!}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End of Contact section
                                                                                                                                                 ============================================= -->

    @if (!empty($announcement))
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-announcement="{{ $announcement->id }}">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ $announcement->title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {!! $announcement->content !!}
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script>
            $(document).ready(function() {
                // Check if a modal with the same data-announcement value has been shown in the past 24 hours
                function showModal() {
                    var announcementId = $('#myModal').data('announcement');
                    if (document.cookie.indexOf("modalShown=" + announcementId + ";") == -1) {
                        $('#myModal').modal('show');
                    }
                }

                showModal();

                // Set a cookie when any modal is closed
                $('.modal').on('hidden.bs.modal', function() {
                    var announcementId = $(this).data('announcement');
                    document.cookie = "modalShown=" + announcementId + "; expires=" + new Date(new Date()
                        .getTime() + 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
                });

                // Set a cookie when the page is closed or refreshed
                $(window).on('beforeunload', function() {
                    var modalId = $('.modal.show').data('announcement');
                    if (modalId) {
                        document.cookie = "modalShown=" + modalId + "; expires=" + new Date(new Date()
                            .getTime() + 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
                    }
                });
            });
        </script>
    @endif

@endsection
