@extends('frontend.layout.app')

@php
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Session;

    $homeStatics=Cache::get('homeStatics'.session()->get('locale'));

//dd(Cache::get('servicesTranslate'.session()->get('locale')));
//       cache::forget('sliderTranslate'.session()->get('locale'));

         $sliderTranslate=Cache::get('sliderTranslate'.session()->get('locale'));

          if($sliderTranslate===null){
                         $sliderdata=[ ];
                  foreach ($slides as $index2 => $slider2) {
                      $sliderdata[$index2]= $tr->translate($slider2->title);
                  }

                 Cache::forever('sliderTranslate'.session()->get('locale'), $sliderdata);
                 $sliderTranslate=Cache::get('sliderTranslate'.session()->get('locale'));
             }


         $servicesTranslate=Cache::get('servicesTranslate'.session()->get('locale'));

              if($servicesTranslate===null){

                         $servicedata=[ ];
                  foreach ($services as $index3 => $service2) {
                      $servicedata[$index3]= ['title' => $tr->translate($service2->title) ,'button' => $tr->translate($service2->button_title)]; ;
                  }

                 Cache::forever('servicesTranslate'.session()->get('locale'), $servicedata);
                  $servicesTranslate=Cache::get('servicesTranslate'.session()->get('locale'));
             }



           if($homeStatics===null){

                 $data=[
                     'Search by Container or VIN Code'=> $tr->translate('Search by Container or VIN Code'),
                     'Search'=> $tr->translate('Search'),
                     'Our Services'=> $tr->translate('Our Services'),
                     'Contact Us'=> $tr->translate('Contact Us'),
                     'Request'=> $tr->translate('Request'),
                     'Name'=> $tr->translate('Name'),
                     'Surname'=> $tr->translate('Surname'),
                     'Email'=> $tr->translate('Email'),
                     'Phone'=> $tr->translate('Phone'),
                     'Send'=> $tr->translate('Send'),
                     'Your Message'=> $tr->translate('Your Message'),

                 ];
                 Cache::forever('homeStatics'.session()->get('locale'), $data);
                  $homeStatics=Cache::get('homeStatics'.session()->get('locale'));
             }

@endphp

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
                                  data-thumb="{{ $slider->media[0]->getUrl() }}" data-in="o:0;" data-out="a:false;">
                            <img src="{{ $slider->media[0]->getUrl() }}" alt="Image" title="slider-1" width="1614"
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
                                      data-type="text" data-rsp_ch="on"
                                      data-xy="xo:15px,12px,9px,5px;yo:340px,280px,212px,124px;"
                                      data-text="w:normal;s:72,59,44,40;l:80,66,50,40;fw:700;" data-frame_0="o:1;"
                                      data-frame_0_chars="d:5;o:0;rX:-90deg;oZ:-50;" data-frame_1="sp:1750;"
                                      data-frame_1_chars="e:power4.inOut;d:10;oZ:-50;" data-frame_999="o:0;st:w;"
                                      style="z-index:9;font-family:'Poppins';">
                                {!! $sliderTranslate[$key] !!}
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
                            <a id="slider-27-slide-70-layer-6" class="rs-layer rev-btn" href="#ft-service"
                               rel="noopener"
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

    {{--search by vin (photoes) --}}
    <section id="ft-booking-form" class="ft-booking-form-section">
        <div class="container">
            <div class="ft-booking-form-content position-relative">
                {{--                <form action="{{ route('customer.searchResult') }}" method="GET">--}}
                <form action="{{ route('customer.car-info') }}" method="GET">

                    <div class="booking-form-input-wrapper d-flex flex-wrap justify-content-center">
                        <label class="booking-form-input position-relative">
                            <span class="booking-form-icon">
{{--                                <i class="flaticon-face-detection"></i>--}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <g fill="none" stroke="#000"
                                           stroke-linecap="round"
                                           stroke-linejoin="round"
                                           stroke-width="1.5" color="#000"><path
                                                    d="m2.5 12l2 1m17-.5l-2 .5M8 17.5l.246-.614c.365-.913.548-1.37.929-1.628c.38-.258.872-.258 1.856-.258h1.938c.984 0 1.476 0 1.856.258s.564.715.93 1.628L16 17.5M2 17v2.882c0 .379.24.725.622.894c.247.11.483.224.769.224h1.718c.286 0 .522-.114.77-.224c.38-.169.621-.515.621-.894V18m11 0v1.882c0 .379.24.725.622.894c.247.11.483.224.769.224h1.718c.286 0 .522-.114.77-.224c.38-.169.621-.515.621-.894V17m-2-8.5l1-.5M4 8.5L3 8m1.5 1l1.088-3.265c.44-1.32.66-1.98 1.184-2.357S7.992 3 9.383 3h5.234c1.391 0 2.087 0 2.61.378c.525.377.745 1.037 1.185 2.357L19.5 9"/><path
                                                    d="M4.5 9h15c.957 1.014 2.5 2.425 2.5 4v3.47c0 .57-.38 1.05-.883 1.117L18 18H6l-3.117-.413C2.38 17.522 2 17.042 2 16.47V13c0-1.575 1.543-2.986 2.5-4"/></g>
                                    </svg>                            </span>
                            <input type="text" minlength="5" name="search"
                                   placeholder="Search By VIN">
                        </label>

                        <button class="ft-sb-button" type="submit">Car Search</button>
                    </div>
                </form>
                <div class="d-flex justify-content-center mt-3">
                    @session('error')
                    <span style="color:red">{{session()->get('error')}}</span>
                    @endsession
                </div>
            </div>
        </div>

    </section>
    {{--search by vin (container) --}}
    <section id="ft-booking-form" class="ft-booking-form-section">
        <div class="container">
            <div style="background: #F6F6F6" class="ft-booking-form-content position-relative">
                {{--                <form action="{{ route('customer.searchResult') }}" method="GET">--}}
                <form action="{{ route('guest.track.container') }}" method="GET">

                    <div class="booking-form-input-wrapper d-flex flex-wrap justify-content-center">

                        <label class="booking-form-input position-relative">
                            <span class="booking-form-icon">
{{--                                <i class="flaticon-face-detection"></i>--}}
                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path
                                               fill="#000"
                                               d="M13.152.682a2.25 2.25 0 0 1 2.269 0l.007.004l6.957 4.276a2.28 2.28 0 0 1 1.126 1.964v7.516c0 .81-.432 1.56-1.133 1.968l-.002.001l-11.964 7.037l-.004.003c-.706.41-1.578.41-2.284 0l-.026-.015l-6.503-4.502a2.27 2.27 0 0 1-1.096-1.943V9.438c0-.392.1-.77.284-1.1l.003-.006l.014-.026c.197-.342.48-.627.82-.827h.002L13.152.681Zm.757 1.295h-.001L2.648 8.616l6.248 4.247a.78.78 0 0 0 .758-.01h.001l11.633-6.804l-6.629-4.074a.75.75 0 0 0-.75.003ZM8.517 14.33a2.3 2.3 0 0 1-.393-.18l-.023-.014l-6.102-4.147v7.003c0 .275.145.528.379.664l.025.014l6.114 4.232zM18 9.709l-3.25 1.9v7.548L18 17.245Zm-7.59 4.438l-.002.002a2.3 2.3 0 0 1-.391.18v7.612l3.233-1.902v-7.552Zm9.09-5.316v7.532l2.124-1.25a.78.78 0 0 0 .387-.671V7.363Z"/></svg>                            </span>
                            <input style="background: #F6F6F6" type="text" minlength="5" name="container"
                                   placeholder="Container Tracking">
                        </label>

                        <button class="ft-sb-button" type="submit">Tracking</button>
                    </div>
                </form>
                <div class="d-flex justify-content-center mt-3">
                    @session('container_error')
                    <span style="color:red">{{session()->get('container_error')}}</span>
                    @endsession
                </div>
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

                                <h2>{!! $homeStatics['Our Services'] !!}</h2>
                            </div>
                            <div class="ft-btn">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="ft-service-slider-area">
                            <div class="ft-service-slider-wrapper">
                                @foreach ($services as $index4 => $service)
                                    <div class="ft-item-innerbox wow fadeInLeft" data-wow-delay="200ms"
                                         data-wow-duration="1500ms">
                                        <div class="ft-service-slider-item">
                                            <div class="ft-service-inner-img">
                                                <img src="{{ $service->media[0]->getUrl() }}"
                                                     alt="{{ $servicesTranslate[$index4]['title'] }}">
                                            </div>
                                            <div class="ft-service-inner-text headline pera-content position-relative">
                                                <h3>
                                                    <a href="{{ $service->button_url }}">{!! $servicesTranslate[$index4]['title'] !!}</a>
                                                </h3>
                                                <a class="service-more"
                                                   href="{{ $service->button_url }}">{!! $servicesTranslate[$index4]['button'] !!}
                                                    <span>+</span>
                                                </a>
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
    <section id="ft-contact" class="ft-contact-section position-relative"
             data-background="frontendAssets/images/cargo.jpeg">
        <div class="container">
            <div class="ft-contact-content">
                <div class="ft-section-title headline pera-content">
                    <span class="sub-title">{!! $homeStatics['Contact Us']  !!}</span>
                    <h2> {!! $homeStatics['Request']  !!}</h2>
                </div>
                <div class="ft-contact-form-wrapper">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('sendEmail') }}" method="POST">
                        @csrf
                        <input style="display: none" type="hidden" name="required">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" required name="fname" placeholder="{!! $homeStatics['Name']  !!}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="lname" placeholder="{!! $homeStatics['Surname']  !!}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" required name="email" placeholder="{!! $homeStatics['Email']  !!}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" required name="phone" placeholder="{!! $homeStatics['Phone'] !!}">
                            </div>
                            <div class="col-md-12">
                                <textarea name="message"
                                          placeholder="{!! $homeStatics['Your Message'] !!}"></textarea>
                            </div>
                            <div class="col-md-12">
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
                                <strong> SMS Consent</strong>
                                <div class="form-group">
                                    <input type="checkbox" id="sms" name="sms"/>
                                    <label for="sms">By submitting this form, you agree to
                                        recive SMS message from our
                                        company regarding our services.</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="ft-sb-button">{!! $homeStatics['Send']  !!}</button>
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
            $(document).ready(function () {
                // Check if a modal with the same data-announcement value has been shown in the past 24 hours
                function showModal() {
                    var announcementId = $('#myModal').data('announcement');
                    if (document.cookie.indexOf("modalShown=" + announcementId + ";") == -1) {
                        $('#myModal').modal('show');
                    }
                }

                showModal();

                // Set a cookie when any modal is closed
                $('.modal').on('hidden.bs.modal', function () {
                    var announcementId = $(this).data('announcement');
                    document.cookie = "modalShown=" + announcementId + "; expires=" + new Date(new Date()
                        .getTime() + 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
                });

                // Set a cookie when the page is closed or refreshed
                $(window).on('beforeunload', function () {
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
