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


@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg"
        style="background-image: url(&quot;https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 50px;">{{$calculatorStatics['Calculator']}}</h2>

            </div>
        </div>
    </section>

    <div class="container parent">
        <div class="row">
            <div class="col">
                <div class="load-type-list">

                    @foreach ($loadtypes as $key => $loadtype)
                        <div class='load-type'>
                            <input type="radio" {{ $loadtype->id == 1 ? 'checked' : 0 }} value="{{ $loadtype['price'] }}"
                                name="loadtype" id="{{ $key }}" class="d-none imgbgchk loadtype">
                            <label for="{{ $key }}">
                                <img src="{{ Storage::url($loadtype->icon) }}" alt="{{ $loadtype['name'] }}">
                                <span>{!! $loadtype['name'] !!}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="col">
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
                    <span id="total"></span>
                    <span id="total_original" style="display: none"></span>

                </div>

                <div class="direct mt-2" style="display: none">
                    <div class="location">
                        <div class="icon city-icon"><img src="https://cdn-icons-png.flaticon.com/512/3909/3909383.png"
                                alt="usa">
                            <div>
                                <div id="cityname">Cityname</div>
                            </div>
                        </div>

                    </div>
                    <hr style="width: 100%;">
                    <div class="location">
                        <div class="icon port-icon">
                            <img src="https://cdn.icon-icons.com/icons2/1468/PNG/512/ship_101088.png" alt="ship">
                            <div>
                                <div id="portname">Portname</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Fetch auction data from the database using AJAX
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var loggedIn = {{ Auth::guard('customer')->user() ? 'true' : 'false' }};

            $('#AuctionSelect').on('change', function() {

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

                    success: function(data) {
                        $('#AucLocationsSelect').empty();

                        // Update auction select options based on received data
                        $('#AucLocationsSelect').append(
                            `<option value="-1">Select Location</option>`
                        );
                        data.forEach(function(location) {
                            $('#AucLocationsSelect').append(
                                `<option value="${location.id}">${location.name}</option>`
                            );
                        });

                    }
                });
            });

            $('#AucLocationsSelect').on('change', function() {
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

                    success: function(data) {
                        $('#AucPortSelect').empty();

                        // Update auction select options based on received data
                        $('#AucPortSelect').append(
                            `<option value="-1">Select Exit Port</option>`
                        );
                        data.forEach(function(location) {
                            $('#AucPortSelect').append(
                                `<option value="${location.id}">${location.name}</option>`
                            );
                        });


                    }
                });
            });

            $('#AucPortSelect').on('change', function() {
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

                    success: function(data) {

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


            $('#AucCountrySelect').on('change', function() {
                $.ajax({
                    url: "{{ route('calculate') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        country: $('#AucCountrySelect').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function(data) {
                        $('#AucPortCitySelect').empty();

                        // Update auction select options based on received data
                        $('#AucPortCitySelect').append(
                            `<option value="-1">Select Port/City</option>`
                        );
                        data.forEach(function(location) {
                            $('#AucPortCitySelect').append(
                                `<option value="${location.id}">${location.name}</option>`
                            );
                        });
                    }
                });
            });


            $('#calculate').on('click', function() {
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

                    success: function(data) {

                        $('#ground_rate').text('$ ' + data.ground_rate);
                        $('#total').text('$ ' + data.total);
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


            // $('input[name="loadtype"]').on('change', function() {
            //     var total_original = $('#total_original').text();

            //     $('#total').text(parseInt(total_original))
            //     var current = $('#total').text();
            //     $('#total').text('')
            //     var loadtype = $('input[name="loadtype"]:checked').val();
            //     $('#total').text(parseInt(current) + parseInt(loadtype));
            // });

            // $('#AucPortCitySelect').on('change', function() {
            //     $.ajax({
            //         url: "{{ route('calculate') }}", // Replace with your actual route name
            //         method: 'POST',
            //         data: {
            //             country_port: $('#AucPortCitySelect').val(),
            //             auction_id: $('#AuctionSelect').val(),
            //             location_id: $('#AucLocationsSelect').val(),
            //             port_id: $('#AucPortSelect').val(),
            //             loadtype: $('input[name="loadtype"]:checked').val(),
            //         },
            //         headers: {
            //             'X-CSRF-TOKEN': csrf_token
            //         },

            //         success: function(data) {

            //             $('#ground_rate').text(data.ground_rate);
            //             $('#total').text(data.total);
            //             $('#total_original').text(data.total);

            //             jQuery('.direct').fadeIn()
            //             $('#cityname').text($("#AucLocationsSelect option:selected").text());
            //             $('#portname').text($("#AucPortCitySelect option:selected").text());




            //             // // Update auction select options based on received data
            //             // $('#AucPortCitySelect').append(
            //             //     `<option value="-1">Select Port/City</option>`
            //             // );
            //             // data.forEach(function(location) {
            //             //     $('#AucPortCitySelect').append(
            //             //         `<option value="${location.id}">${location.name}</option>`
            //             //     );
            //             // });


            //         }
            //     });
            // });


        }); // End of $(document).ready
    </script>
@endsection
