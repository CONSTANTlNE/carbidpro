@extends('layout.app')

@section('content')
    @push('style')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
        <style>
            .splide__slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        </style>
    @endpush
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/assets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{!! $tr->translate('Car:') !!} {{ $car->vin }}</h2>

            </div>
        </div>
    </section>

    <div class="container">
        <div class="car-information__back-button">
            <a href="{{ route('customer.dashboard') }}">
                < Back to my cars </a>
        </div>
        <div class="row">
            <div class="col-lg-5 mt-5 mb-5">

                @if ($car->getMedia()->first())
                    <section id="image-carousel" class="splide" aria-label="Beautiful Images">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($car->getMedia() as $image)
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
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($car->getMedia() as $image)
                                <li class="splide__slide">
                                    <img src="{{ $image->getUrl() }}" alt="">

                                </li>
                            @endforeach


                        </ul>
                    </div>


                </section>
                @if ($car->getMedia()->first())
                    <a class="d-flex justify-content-end" style="gap: 10px;    margin-top: 10px;"
                        href="{{ route('customer.download_images', $car->vin) }}"><img width="20px"
                            src="https://www.svgrepo.com/show/140007/download-button.svg">Download Images</a>
                @endif
            </div>

            <div class="col-lg-7 mt-5 mb-5">
                <div class="car_information">
                    <h1>{{ $car->year . ' ' . $car->make . ' ' . $car->model }}</h1>
                    <h3>{{ $car->debit }}</h3>
                </div>

                <dl class="car-information__car-information">
                    <dd class="car-information__car-information-item">
                        <label>VIN:</label><span>
                            @isset($car->vin)
                                {{ $car->vin }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Year:</label><span>
                            @isset($car->year)
                                {{ $car->year }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Make:</label><span>
                            @isset($car->make)
                                {{ $car->make }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Model:</label><span>
                            @isset($car->model)
                                {{ $car->model }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Exterior color:</label><span>
                            @isset($car->exterior_color)
                                {{ $car->exterior_color }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Body style:</label><span>
                            @isset($car->body_style)
                                {{ $car->body_style }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Engine Type:</label><span>
                            @isset($car->engine_type)
                                {{ $car->engine_type }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Type:</label><span>
                            @isset($car->loadType->name)
                                {{ $car->loadType->name }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Fuel:</label><span>
                            @isset($car->fuel)
                                {{ $car->fuel }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Key:</label><span>
                            @isset($car->key)
                                {{ $car->key }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Original State:</label><span>
                            @isset($car->state->name)
                                {{ $car->state->name }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Title M Date:</label><span>
                            @isset($car->title_m_date)
                                {{ $car->title_m_date }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Lot:</label><span>
                            @isset($car->lot)
                                {{ $car->lot }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Auction:</label><span>
                            @isset($car->auction->name)
                                {{ $car->auction->name }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Due data:</label><span>
                            @isset($car->due_data)
                                {{ $car->due_data }}
                            @endisset
                        </span>
                    </dd>
                </dl>

                <dl class="car-information__car-information">
                    <dd class="car-information__car-information-item">
                        <label>Container:</label><span>
                            @isset($car->container)
                                {{ $car->container }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Booking:</label><span>
                            @isset($car->booking)
                                {{ $car->booking }}
                            @endisset
                        </span>
                    </dd>
                    {{-- <dd class="car-information__car-information-item">
                        <label>Shipment line:</label><span>
                            @isset($car->shipment_line)
                                {{ $car->shipment_line }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Port of Origin:</label><span>
                            @isset($car->port_of_origin)
                                {{ $car->port_of_origin }}
                            @endisset
                        </span>
                    </dd> --}}
                    {{-- <dd class="car-information__car-information-item">
                        <label>Destination:</label><span>
                            @isset($car->toPort->name)
                                {{ $car->toPort->name }}
                            @endisset
                        </span>
                    </dd> --}}
                    {{-- <dd class="car-information__car-information-item">
                        <label>Place of Delivery:</label><span>
                            @isset($car->toPort->name)
                                {{ $car->toPort->name }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>Vessel:</label><span>
                            @isset($car->vessel)
                                {{ $car->vessel }}
                            @endisset
                        </span>
                    </dd> --}}
                    <dd class="car-information__car-information-item">
                        <label>Loading Date:</label><span>
                            @isset($car->loading_date)
                                {{ $car->loading_date }}
                            @endisset
                        </span>
                    </dd>
                    {{-- <dd class="car-information__car-information-item">
                        <label>ETD:</label><span>
                            @isset($car->etd)
                                {{ $car->etd }}
                            @endisset
                        </span>
                    </dd>
                    <dd class="car-information__car-information-item">
                        <label>ETA:</label><span>
                            @isset($car->eta)
                                {{ $car->eta }}
                            @endisset
                        </span>
                    </dd> --}}
                </dl>


            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
