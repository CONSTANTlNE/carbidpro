@extends('frontend.layout.app')
@php
    use Carbon\Carbon;
@endphp

@section('content')
    @push('style')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
        <style>
            .splide__slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .ft-project-overview-list-item li:before {
                top: 0;
                left: 0;
                content: '•';
                font-weight: 900;
                position: absolute;
                color: black;
                /*font-family: "Font Awesome 5 Pro";*/
            }
        </style>
    @endpush
    {{--    @dd($car->groups[0]->est_open_date,Carbon::parse($car->groups[0]->est_open_date)->format('d-m-Y'))--}}

    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
             data-background="/frontendAssets/images/cargo.jpeg"
             style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                {{--                {!! $tr->translate('Car') !!}--}}
                <h2 style="margin-top: 80px;padding: 0;">VIN: {{ $car->vin }}</h2>

            </div>
        </div>
    </section>

    <div class="container">
        <div class="car-information__back-button text-center">
            {{--            <a href="{{ route('customer.dashboard') }}">--}}
            {{--                < Back to my cars </a>--}}
        </div>
        <div class="row">
            <div class="col-lg-5 mt-5 mb-5">

                @if ($car->media->first())
                    <section id="image-carousel" class="splide" aria-label="Beautiful Images">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($car->getMedia('images') as $image)
                                    <li class="splide__slide">
                                        <a href="{{ $image->getUrl() }}" data-fancybox="gallery" data-caption="">
                                            <img src="{{ $image->getUrl() }}" alt="">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                @endif


                <section id="thumbnail-carousel" class="splide"
                         aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
                    <div class="splide__track mt-2">
                        <ul class="splide__list justify-content-center">
                            @foreach ($car->getMedia('images') as $image)
                                <li class="splide__slide">
                                    <img src="{{ $image->getUrl() }}" alt="">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>

                @if ($car->media->first())
                    <a class="d-flex justify-content-center mt-3"
                       href="{{ route('customer.download_images', $car->vin) }}">
                        <img width="20px" src="https://www.svgrepo.com/show/140007/download-button.svg">
                        Download Images
                    </a>
                @endif
            </div>
            <div class="col-lg-7 mt-5 mb-5">
                <div style="border: 1px solid black;" class="car_information  mb-3">
                    <h1 class="text-center">{{ $car->make_model_year}}</h1>
                </div>

                <div class="row justify-content-between">
                    <div class="col-lg-5">
                        <div class="ft-project-overview-list-item ul-li-block">
                            <ul>
                                <li><span style="color: black!important;">VIN :</span> {{ $car->vin}}</li>
                                <li>
                                    <span style="color: black!important;"> Car Type :</span> {{ $car->loadType->name}}
                                </li>
                                <li><span style="color: black!important;">Fuel : </span> {{ $car->type_of_fuel}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="ft-project-overview-list-item ul-li-block">
                            <ul>
{{--                                <li><span style="color: black!important;">State :</span> {{ $car->state->name}}--}}
{{--                                </li>--}}
                                <li><span style="color: black!important;">Auction :</span> {{ $car->Auction->name}}
                                </li>
                                <li><span style="color: black!important;">LOT :</span> {{ $car->lot}} </li>
                                <li><span style="color: black!important;">Title :</span> {{ $car->title}} </li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div style="border: 1px solid black;background: #9cc2e5" class="row justify-content-between mt-5 pt-2 ">
                    <div class="col-lg-5">
                        <div class="ft-project-overview-list-item ul-li-block">
                            <ul>
                                <li>
                                        <span style="color: black!important;">Container :

{{--                                            {{ $car->groups->first()?->container_id ? $car->groups->first()?->container_id : 'N/A'}}--}}
                                            @if($car->groups->first()?->container_id )
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
                                                >
                                                    <span id="container_id"> {{$car->groups->first()?->container_id}}</span>

                                                </a>
                                                <svg
                                                        onclick="navigator.clipboard.writeText(document.getElementById('container_id').textContent)

                                                        "
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24"><g fill="none" stroke="#000"
                                                                               stroke-width="1.5"><path
                                                                d="M6 11c0-2.828 0-4.243.879-5.121C7.757 5 9.172 5 12 5h3c2.828 0 4.243 0 5.121.879C21 6.757 21 8.172 21 11v5c0 2.828 0 4.243-.879 5.121C19.243 22 17.828 22 15 22h-3c-2.828 0-4.243 0-5.121-.879C6 20.243 6 18.828 6 16z"/><path
                                                                d="M6 19a3 3 0 0 1-3-3v-6c0-3.771 0-5.657 1.172-6.828S7.229 2 11 2h4a3 3 0 0 1 3 3"/></g></svg>
                                            @else
                                                N/A
                                            @endif
                                        </span>

                                </li>
                                <li>
                                    <span style="color: black!important;">Shipping Line :  {{ $car->groups->first()?->thc_agent ? $car->groups->first()?->thc_agent : 'N/A'}}</span>
                                </li>
                                <li>
                                    <span style="color: black!important;">ETA : {{$car->groups->first()?->arrival_time? Carbon::parse($car->groups->first()?->arrival_time)->format('d-m-Y'): 'N/A'}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="ft-project-overview-list-item ul-li-block">
                            <ul>

                                <li>
                                    <span style="color: black!important;">Open Date (est) : {{ $car->groups->first()?->est_open_date ? Carbon::parse($car->groups->first()?->est_open_date)->format('d-m-Y') : 'N/A'}}</span>
                                </li>
                                <li>
                                    <span style="color: black!important;">Terminal  : {{ $car->groups->first()?->terminal ? $car->groups->first()?->terminal : 'N/A'}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Fancybox.bind('[data-fancybox="gallery"]', {
                    Thumbs: {
                        type: "classic"
                    }
                });
                var main = new Splide('#image-carousel', {
                    type: 'fade',
                    rewind: true,
                    pagination: false,
                    arrows: false,
                });


                var thumbnails = new Splide('#thumbnail-carousel', {
                    fixedWidth: 100,
                    fixedHeight: 60,
                    gap: 10,
                    rewind: true,
                    pagination: false,
                    isNavigation: true,
                    breakpoints: {
                        600: {
                            fixedWidth: 60,
                            fixedHeight: 44,
                        },
                    },
                });


                main.sync(thumbnails);
                main.mount();
                thumbnails.mount();

            });
        </script>
    @endpush
@endsection
