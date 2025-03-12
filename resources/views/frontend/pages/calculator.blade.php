@extends('frontend.layout.app')


@php

    //                      Cache::forget('calculatorStaticsen');
    //                      Cache::forget('calculatorStaticsru');

            $calculatorStatics=Cache::get('calculatorStatics'.session()->get('locale'));

                      if($calculatorStatics===null){

                          $data=[
                              'Name'=>$tr->translate('Name'),
                              'Calculator'=>$tr->translate('Calculator'),
                              'Select Auction'=>$tr->translate('Select Auction'),
                              'Select Location'=>$tr->translate('Select Location'),
                              'Select Country'=>$tr->translate('Select Country'),
                              'Select Exit Port'=>$tr->translate('Select Exit Port'),
                              'Select Port/City'=>$tr->translate('Select Port/City'),
                              'Calculate'=>$tr->translate('Calculate'),
                              'GROUND RATE'=>$tr->translate('GROUND RATE'),
                              'TOTAL'=>$tr->translate('TOTAL'),
                              ];
                          Cache::forever('calculatorStatics'.session()->get('locale'), $data);
                          $calculatorStatics=Cache::get('calculatorStatics'.session()->get('locale'));
                      }

@endphp
@push('style')

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-wrapper {
            min-width: 250px;
            padding: 0;
        }
    </style>
@endpush


@section('content')

    {{--    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"--}}
    {{--             data-background="https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg"--}}
    {{--             style="background-image: url(&quot;https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg&quot;);">--}}
    {{--        <span class="background_overlay"></span>--}}
    {{--        <div class="container">--}}
    {{--            <div class="ft-breadcrumb-content headline text-center position-relative">--}}
    {{--                <h2 style="margin-top: 50px;">{{$calculatorStatics['Calculator']}}</h2>--}}

    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}

    <section class="ft-project-page-section page-padding" style="margin-top: 40px">
        <div class="container">
            <div class="row">
                <div style="padding: 0" class="col">
                    <div class="load-type-list">
                        @foreach ($loadtypes as $key => $loadtype)
                            <div class='load-type'>
                                <input type="radio"
                                       {{ $loadtype->id == 1 ? 'checked' : 0 }} value="{{ $loadtype['price'] }}"
                                       name="loadtype" id="{{ $key }}" class="d-none imgbgchk loadtype">
                                <label for="{{ $key }}">
                                    <img src="{{ Storage::url($loadtype->icon) }}" alt="{{ $loadtype['name'] }}">
                                    <span>{!! $loadtype['name'] !!}</span>
                                    @if ($loadtype['name'] == 'Big SUV')
                                        <img id="bigsuvinfo" src="https://static.thenounproject.com/png/2392586-200.png"
                                             alt="Big SUV" style="max-width: 15px;margin-left: 10px;">
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div style="padding: 0" class="col">
                    <div class="auction_select">

                        <select class="form-select mb-2" id="AuctionSelect">
                            <option value="-1">{{$calculatorStatics['Select Auction']}}</option>
                            @foreach ($auctions as $auction)
                                <option value="{{ $auction->id }}">{{ $auction->name }}</option>
                            @endforeach
                        </select>

                        <input type="hidden" name="difflocation_id" id="difflocation_id" value="0">

                        <select class="form-select mb-2" id="AucLocationsSelect">
                            <option value="-1" selected="">{{$calculatorStatics['Select Location']}}</option>
                        </select>

                        <select class="form-select mb-2" id="AucPortSelect">
                            <option value="-1" selected="">{{$calculatorStatics['Select Exit Port']}}</option>
                        </select>

                        <select class="form-select mb-2" id="AucCountrySelect">
                            <option value="-1" selected="">{!! $calculatorStatics['Select Country'] !!}</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>

                        <select class="form-select mb-2" id="AucPortCitySelect">
                            <option value="-1" selected="">{{$calculatorStatics['Select Port/City']}}</option>
                        </select>

                        <button class="btn btn-success" id="calculate">{{$calculatorStatics['Calculate']}}</button>

                    </div>
                </div>
                <div class="col">
                    <div class="pricing ground_rate mt-3" style="display: none">
                        <strong>{{$calculatorStatics['GROUND RATE']}}:</strong>
                        <span id="ground_rate"></span>
                    </div>
                    <br>
                    <div class="pricing total">
                        <strong>{{$calculatorStatics['TOTAL']}}:</strong>
                        <span style="margin-right: 20px" id="total"></span>
                        <span id="total_original" style="display: none"></span>
                    </div>
                    <div class="direct mt-2" style="display: none">
                        <div class="location">
                            <div class="icon city-icon"><img
                                        src="https://cdn-icons-png.flaticon.com/512/3909/3909383.png"
                                        alt="usa">
                                <div>
                                    <div id="cityname">Cityname</div>
                                </div>
                            </div>

                        </div>
                        <hr style="width: 100%;">
                        <div class="location">
                            <div class="icon port-icon text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512">
                                    <mask id="a">
                                        <circle cx="256" cy="256" r="256" fill="#fff"/>
                                    </mask>
                                    <g mask="url(#a)">
                                        <path fill="#eee"
                                              d="M0 0h224l32 32 32-32h224v224l-32 32 32 32v224H288l-32-32-32 32H0V288l32-32-32-32Z"/>
                                        <path fill="#d80027"
                                              d="M224 0v224H0v64h224v224h64V288h224v-64H288V0h-64zm-96 96v32H96v32h32v32h32v-32h32v-32h-32V96h-32zm224 0v32h-32v32h32v32h32v-32h32v-32h-32V96h-32zM128 320v32H96v32h32v32h32v-32h32v-32h-32v-32h-32zm224 0v32h-32v32h32v32h32v-32h32v-32h-32v-32h-32z"/>
                                    </g>
                                </svg>
                                {{--                            <img src="https://cdn.icon-icons.com/icons2/1468/PNG/512/ship_101088.png" alt="ship">--}}
                                <div>
                                    <div id="portname">Portname</div>
                                </div>
                            </div>

                        </div>
                    </div>
{{--                    @if($user->extra_expenses != null)--}}
{{--                        <div class="d-flex flex-column mt-3  ">--}}
{{--                            @php--}}
{{--                                $extras=json_decode($user->extra_expenses);--}}
{{--                            @endphp--}}
{{--                            @foreach($extras as $index => $extra)--}}
{{--                                @if($index>0)--}}
{{--                                    <div class="d-flex justify-content-between align-items-middle mt-2">--}}
{{--                                        <div class="d-flex justify-content-between align-middle"--}}
{{--                                             style="min-width:130px; margin-right: 10px ">--}}
{{--                                            <label style="cursor: pointer" class="mt-1"--}}
{{--                                                   for="{{$extra->name}}">{{$extra->name}}</label>--}}
{{--                                            <input style="cursor: pointer" class="form-check-input extra-checkbox"--}}
{{--                                                   id="{{$extra->name}}"--}}
{{--                                                   type="checkbox" value="{{$extra->value}}}">--}}
{{--                                        </div>--}}
{{--                                        <input disabled class="form-control text-center" style="max-width: 150px"--}}
{{--                                               type="text"--}}
{{--                                               value="{{$extra->value}}">--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    @endif--}}
                </div>
                {{--  titles--}}
                <div style="display: flex;flex-direction: column; align-content: center!important;justify-content: center;margin-top: 30px">
                    <div style="align-self: center" class="flex-col justify-content-center">
                        <p class="text-center" style="margin-bottom: 0">Check TITLE</p>
                        <select id="select-beast" autocomplete="off" onchange="titleStatus(this.value)"
                                class="form-control text-center" style="min-width: 330px">
                            <option value="">Enter Title name</option>
                            @foreach($titles as $title)
                                <option value="{{$title->id}}">{{$title->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="align-self: center" class="flex-col  justify-content-center mt-3">
                        <p class="text-center" style="margin-bottom: 0">Status</p>
                        <input id="titleStatus" disabled class="input-group-first text-center" style="max-width: 150px"
                               type="text">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
            crossorigin="anonymous"></script>


    <div class="bigsuvinfo">
        <div class="closebtn">X</div>
        <ul>
            <li>Rolls-Royce Cullinan.</li>

            <li>Infiniti QX80.</li>

            <li>Chevrolet Tahoe.</li>

            <li> Nissan Armada.</li>

            <li> Jeep Grand Wagoneer.</li>

            <li> Ford Expedition.</li>

            <li> GMC Yukon.</li>

            <li> Lincoln Navigator.</li>

            <li> Cadillac Escalade.</li>
            <li>Lexus LX.</li>
            <li> Jeep Wagoneer.</li>

            <li> Chevrolet Suburban.</li>

            <li> Toyota Sequoia.</li>
            <li> BMW X7.</li>

            <li> Mercedes-Benz GLS.</li>

            <li> Mercedes-Benz GL.</li>

            <li> Land Rover Range Rover.</li>

            <li> Volkswagen Atlas.</li>

            <li>Audi Q7.</li>

            <li> Hyundai Palisade.</li>

            <li> Toyota Land Cruiser.</li>

            <li> Mazda CX-9.</li>

            <li> Dodge Durango.</li>
        </ul>
    </div>
    <style>
        .bigsuvinfo {
            display: none;
            background: #1a7fca;
            max-width: 700px;
            flex-wrap: wrap;
            border-radius: 10px;
            padding: 25px;
            justify-content: center;
            margin: 0 auto;
            top: 30%;
            position: fixed;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            z-index: 9999;
            right: 0;
        }

        .bigsuvinfo ul {
            display: flex;
            flex-wrap: wrap;
        }

        .closebtn {
            position: relative;
            top: -10px;
            color: #fff;
            display: flex;
            justify-content: center;
            margin: 0 0 0 auto;
            cursor: pointer;
            z-index: 999;
        }

        .bigsuvinfo li {
            color: #ffffff;
            max-width: 50%;
            list-style: none;
            width: 100%;
        }

        .container {
            max-width: 83.333vw;
        }

        @media (min-width: 320px) and (max-width: 480px) {


            .parent > .row {
                flex-direction: column;
            }

            #bigsuvinfo {
                max-width: 15px;
                margin-left: 10px;
            }

            .bigsuvinfo {
                display: none;
                position: fixed;
                background: #1a7fca;
                max-width: 100%;
                flex-wrap: wrap;
                border-radius: 10px;
                padding: 15px;
                left: 0px;
                bottom: 100px;
                margin: 10px;
            }

            .bigsuvinfo ul {
                display: flex;
                flex-wrap: wrap;
                padding: 0;
            }
        }
    </style>

    {{--    title select script--}}
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                new TomSelect("#select-beast", {
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            }, 200)
        })

        const titles = {!! $titles !!};
        const userTitles = {!! $user->titles !!};


        function titleStatus(id) {
            titles.forEach((title) => {

                if (title['id'] == id) {
                    let found = false;
                    // if  user has custom title status
                    if (userTitles.length > 0) {
                        userTitles.forEach((usertitle) => {
                            if (!found && id == usertitle['id']) {
                                document.getElementById('titleStatus').value = usertitle['pivot']['title_for_customer'];
                                found = true; // Prevents further changes
                            } else if (!found) {
                                document.getElementById('titleStatus').value = title['status'];
                            }
                        });

                    } else {
                        // if user does not have custom title status
                        document.getElementById('titleStatus').value = title['status']
                    }
                }
            })
        }

    </script>


    {{--    big suv info--}}
    <script>
        $('#bigsuvinfo').on('click', function () {
            var bigsuvinfo = $('.bigsuvinfo');

            if (bigsuvinfo.css('display') === 'none') {
                bigsuvinfo.css('display', 'flex');
            } else {
                bigsuvinfo.css('display', 'none');
            }
        });

        $('.closebtn').on('click', function () {
            var bigsuvinfo = $('.bigsuvinfo');

            if (bigsuvinfo.css('display') === 'none') {
                bigsuvinfo.css('display', 'flex');
            } else {
                bigsuvinfo.css('display', 'none');
            }
        });
    </script>

    {{--calculation--}}
    <script>


        // Calculate Customers extra expenses and add to shipping price
        {{--let totalextras = 0;--}}

        {{--@if($user->extra_expenses != null)--}}
        {{--const extras = {!!$user->extra_expenses !!};--}}
        {{--// console.log(extras);--}}

        {{--const checkboxes = document.querySelectorAll('.extra-checkbox');--}}
        {{--checkboxes.forEach((checkbox) => {--}}
        {{--    checkbox.addEventListener('change', function () {--}}
        {{--        let value = parseFloat(this.value);--}}
        {{--            if (this.checked) {--}}
        {{--                totalextras += value;--}}
        {{--            } else {--}}
        {{--                totalextras -= value;--}}
        {{--            }--}}
        {{--            setTimeout(() => {--}}
        {{--                document.getElementById('calculate').click();--}}
        {{--            }, 100)--}}
        {{--    });--}}
        {{--});--}}
        {{--@endif--}}

        // Calculate Shipping price

        $(document).ready(function () {
            // Fetch auction data from the database using AJAX
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var loggedIn = {{ Auth::guard('customer')->user() ? 'true' : 'false' }};

            $('#AuctionSelect').on('change', function () {

                if (!loggedIn) {
                    window.location.href = "/customer/login";
                }

                $.ajax({
                    url: "{{ route('calculate') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        auction_id: $('#AuctionSelect').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function (data) {
                        $('#AucLocationsSelect').empty();

                        // Update auction select options based on received data
                        $('#AucLocationsSelect').append(
                            `<option value="-1">Select Location</option>`
                        );
                        data.forEach(function (location) {
                            $('#AucLocationsSelect').append(
                                `<option value="${location.id}">${location.name}</option>`
                            );
                        });

                    }
                });
            });

            $('#AucLocationsSelect').on('change', function () {
                $.ajax({
                    url: "{{ route('calculate') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        port: true,
                        auction_id: $('#AucLocationsSelect').val(),
                        location_name: $('#AucLocationsSelect option:selected').text(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function (data) {
                        $('#AucPortSelect').empty();

                        // Update auction select options based on received data
                        $('#AucPortSelect').append(
                            `<option value="-1">Select Exit Port</option>`
                        );
                        data.forEach(function (location) {
                            $('#AucPortSelect').append(
                                `<option value="${location.id}">${location.name}</option>`
                            );
                        });


                    }
                });
            });

            $('#AucPortSelect').on('change', function () {
                $.ajax({
                    url: "{{ route('calculate') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        auction_id: $('#AuctionSelect').val(),
                        location_id: $('#AucLocationsSelect').val(),
                        port_id: $('#AucPortSelect').val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function (data) {

                        if (!data.price) {
                            console.log(" CONTACT ");
                        } else {

                            console.log(data.price);
                        }

                        // $('#AucPortSelect').empty();

                        // // Update auction select options based on received data
                        // $('#AucPortSelect').append(
                        //     `<option value="-1">Select Exit Port</option>`
                        // );
                        // data.forEach(function(location) {
                        //     $('#AucPortSelect').append(
                        //         `<option value="${location.id}">${location.name}</option>`
                        //     );
                        // });

                    }
                });
            });

            $('#AucCountrySelect').on('change', function () {
                $.ajax({
                    url: "{{ route('calculate') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        country: $('#AucCountrySelect').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function (data) {
                        $('#AucPortCitySelect').empty();

                        // Update auction select options based on received data
                        $('#AucPortCitySelect').append(
                            `<option value="-1">Select Port/City</option>`
                        );
                        data.forEach(function (location) {
                            $('#AucPortCitySelect').append(
                                `<option value="${location.id}">${location.name}</option>`
                            );
                        });
                    }
                });
            });

            $('#calculate').on('click', function () {
                $.ajax({
                    url: "{{ route('calculate') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        country_port: $('#AucPortCitySelect').val(),
                        auction_id: $('#AuctionSelect').val(),
                        location_id: $('#AucLocationsSelect').val(),
                        port_id: $('#AucPortSelect').val(),
                        loadtype: $('input[name="loadtype"]:checked').val(),
                        location_name: $('#AucLocationsSelect option:selected').text(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function (data) {

                        $('#ground_rate').text('$ ' + data.ground_rate);
                        // $('#total').text('$ ' + (data.total + totalextras));
                        $('#total').text('$ ' + (data.total ));
                        $('#total_original').text('$ ' + data.total);

                        jQuery('.direct').fadeIn()
                        $('#cityname').text($("#AucLocationsSelect option:selected").text());
                        $('#portname').text($("#AucPortCitySelect option:selected").text());


                        // // Update auction select options based on received data
                        // $('#AucPortCitySelect').append(
                        //     `<option value="-1">Select Port/City</option>`
                        // );
                        // data.forEach(function(location) {
                        //     $('#AucPortCitySelect').append(
                        //         `<option value="${location.id}">${location.name}</option>`
                        //     );
                        // });


                    }
                });
            });

        });
    </script>
@endsection
