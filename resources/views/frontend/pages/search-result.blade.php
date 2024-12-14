@extends('frontend.layout.app')

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
                @if (!isset($container))
                    <h2 style="margin-top: 80px;padding: 0;">{!! $tr->translate('Car:') !!} {{ $car->vin }}</h2>
                @else
                    <h2 style="margin-top: 80px;padding: 0;">{!! $tr->translate('Container:') !!} {{ $_GET['search'] }}</h2>
                @endif
            </div>
        </div>
    </section>

    <div class="container">
        <div class="car-information__back-button">
            <a href="/">
                < {!! $tr->translate('Back to Home') !!} </a>
        </div>
        <div class="row">

            @if (!isset($container))

                <style>
                    .splide__slide.is-active.is-visible img {
                        object-fit: cover;
                        height: 500px !important;
                    }
                </style>

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
                    </div>

                    <dl class="car-information__car-information">
                        @if (!empty($car->vin))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('VIN') !!}:</label><span>{{ $car->vin }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->year))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Year') !!}:</label><span>{{ $car->year }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->make))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Make') !!}:</label><span>{{ $car->make }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->model))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Model') !!}:</label><span>{{ $car->model }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->exterior_color))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Exterior color') !!}:</label><span>{{ $car->exterior_color }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->body_style))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Body style') !!}:</label><span>{{ $car->body_style }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->engine_type))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Engine Type') !!}:</label><span>{{ $car->engine_type }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->fuel))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Fuel') !!}:</label><span>{{ $car->fuel }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->key))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Key') !!}:</label><span>{{ $car->key == 1 ? 'YES' : 'NO' }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->title_m_date))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Title M Date') !!}:</label><span>{{ $car->title_m_date }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->lot))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Lot') !!}:</label><span>{{ $car->lot }}</span>
                            </dd>
                        @endif

                        @if (isset($car->auction->name))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Auction') !!}:</label><span>{{ $car->auction->name }}</span>
                            </dd>
                        @endif

                        @if (!empty($car->due_date))
                            <dd class="car-information__car-information-item">
                                <label>{!! $tr->translate('Due Date') !!}:</label><span>{{ $car->due_date }}</span>
                            </dd>
                        @endif
                    </dl>


                    <dl class="car-information__car-information">
                        <dd class="car-information__car-information-item">
                            <label>{!! $tr->translate('Container') !!}:</label><span>{{ $car->container }}</span>
                        </dd>
                        <dd class="car-information__car-information-item">
                            <label>{!! $tr->translate('Booking') !!}:</label><span>{{ $car->booking }}</span>
                        </dd>
                        {{-- <dd class="car-information__car-information-item">
                            <label>{!! $tr->translate('Shipment line') !!}:</label><span>{{ $car->shipment_line }}</span>
                        </dd> --}}
                        {{-- <dd class="car-information__car-information-item">
                            <label>{!! $tr->translate('Port of Origin') !!}:</label><span>{{ $car->port_of_origin }}</span>
                        </dd> --}}
                        {{-- <dd class="car-information__car-information-item">
                            <label>{!! $tr->translate('Destination') !!}:</label><span>{{ $car->toPort->name }}</span>
                        </dd>
                        --}}

                        <dd class="car-information__car-information-item">
                            <label>{!! $tr->translate('Loading Date') !!}:</label><span>{{ $car->loading_date }}</span>
                        </dd>

                    </dl>

                </div>
            @else
                <script>
                    window.open('https://sirius.searates.com/tracking?container={{ $container }}', '_blank');
                    setTimeout(function() {
                        history.back();
                    }, 5000); // 5000 milliseconds = 5 seconds
                </script>
                <iframe width="100%" height="800px"
                    src="https://sirius.searates.com/tracking?container={{ $_GET['search'] }}" frameborder="0"></iframe>
            @endif

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
