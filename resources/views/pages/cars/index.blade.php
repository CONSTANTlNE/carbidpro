@extends('layouts.app')

@section('content')
    @push('css')
        <style>
            .table-responsive td {
                align-content: center;
            }

            .buttonexport {
                padding: 5px 15px;
            }
        </style>
    @endpush
{{--@section('body-class', 'hold-transition sidebar-mini sidebar-collapse')--}}

<!--preloader-->
{{--<div id="preloader">--}}
{{--    <div id="status"></div>--}}
{{--</div>--}}

<!-- Site wrapper -->
<div class="wrapper">
    @include('partials.header')
    <!-- =============================================== -->
    <!-- Left side column. contains the sidebar -->
    @include('partials.aside')
    <!-- =============================================== -->

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="header-icon">
                <i class="fa fa-automobile"></i>
            </div>
            <div class="header-title">
                <h1>Cars</h1>
                <small>List of Car</small>
            </div>
        </section>
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success"
                    style="text-align: center;font-size: 15px;text-transform: uppercase;font-weight: bold;max-width: 400px;margin: 1rem  auto 0;">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12 pinpin">
                    <div class="card lobicard" id="lobicard-custom-control" data-sortable="true">

                        <div class="card-body">
                            <!-- Plugin content:powerpoint,txt,pdf,png,word,xl -->
                            <div class="btn-group d-flex" role="group">
                                <div class="row" style="justify-content: space-between;width: 100%;">

                                    <div class="buttonexport">
                                        @if (auth()->user()->role == 'Admin')
                                            <a href="{{ route('car.create') }}" class="btn btn-add"><i
                                                    class="fa fa-plus"></i>
                                                Add Car</a>
                                        @endif

                                        @php
                                            $currentStatus = request()->segment(count(request()->segments()));

                                        @endphp

                                        @foreach ($car_status as $status)
                                            @php
                                                $hasError = '';
                                                $errorCount = 0; // Counter to count how many cars have errors

                                                if ($status->id == 7) {
                                                    // Check if any car related to this status has title_delivery set to 'no'
                                                    $hasError = $status->cars->contains(function ($car) {
                                                        return $car->title_delivery == 'no' ||
                                                            !$car->getMedia('images')->isNotEmpty();
                                                    });

                                                    // Check for errors and count them
                                                    $errorCount = $status->cars
                                                        ->filter(function ($car) {
                                                            return $car->title_delivery == 'no' ||
                                                                !$car->getMedia('images')->isNotEmpty();
                                                        })
                                                        ->count();

                                                    // Set $hasError to true if there are any errors
                                                    $hasError = $errorCount > 0;
                                                }

                                                if ($status->id == 4 || $status->id == 5) {
                                                    $hasError = $status->cars->contains(function ($car) {
                                                        $pickupDates = $car->pickup_dates;
                                                        $pickupDateExpired = false;

                                                        if ($pickupDates) {
                                                            $dates = explode(' - ', $pickupDates);
                                                            if (count($dates) == 2) {
                                                                $secondDate = \Carbon\Carbon::createFromFormat(
                                                                    'd.m.Y',
                                                                    trim($dates[1]),
                                                                );
                                                                if ($secondDate && now()->greaterThan($secondDate)) {
                                                                    $pickupDateExpired = true;
                                                                }
                                                            }
                                                        }
                                                        return $pickupDateExpired;
                                                    });
                                                }
                                            @endphp
                                            <a href="{{ route('car.showStatus', $status->slug) }}"
                                                class="btn {{ $hasError ? 'btn-danger' : ($currentStatus == $status->slug ? 'btn-primary' : 'btn-secondary') }}">
                                                {{ $status->name }} {{ $status->id == 7 ?  $errorCount :  $status->cars_count }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <div>
                                        <form class="form-inline my-2 my-lg-0" method="GET">
                                            <input class="form-control mr-sm-2"
                                                value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}"
                                                name="search" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if ($currentStatus == 'for-dispatch')
                                @include('partials.car.table-1')
                            @elseif ($currentStatus == 'listed')
                                @include('partials.car.table-2')
                            @elseif($currentStatus == 'assign')
                                @include('partials.car.table-3')
                            @elseif($currentStatus == 'pick-up')
                                @include('partials.car.table-4')
                            @elseif($currentStatus == 'delivered')
                                @include('partials.car.table-5')
                            @elseif($currentStatus == 'payment')
                                @include('partials.car.table-6')
                            @elseif($currentStatus == 'dispatched')
                                @include('partials.car.table-7')
                            @else
                                @include('partials.car.table-default')
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <!-- Delete Car Modal -->
            <div class="modal fade" id="deleteCarModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-user m-r-5"></i> Delete Car</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this Car?</p>
                            <form id="deleteCarForm">
                                @csrf <!-- CSRF token for security -->
                                <input type="hidden" id="deleteCarId" name="car_id">
                                <!-- Hidden field to hold the user ID -->
                                <div class="form-group user-form-group">
                                    <div class="float-right">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-dismiss="modal">NO</button>
                                        <button type="submit" class="btn btn-add btn-sm">YES</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
        </section>
        <!-- /.content -->
    </div>


    @include('partials.footer')

    @push('js')
        <script>
            $('.copy').on('click', function() {
                const text = $(this).prev('strong').find('span').text().trim();
                navigator.clipboard.writeText(text).then(function() {
                    console.log('Copied to clipboard:', text);
                }).catch(function(error) {
                    console.error('Failed to copy:', error);
                });
            });
        </script>


        <script>
            $(function() {
                var availableWarehouse = [
                    "MTL- New jersey",
                    "TRT - New Jersey"
                ];

                $("#warehouse").autocomplete({
                    source: availableWarehouse,
                    minLength: 0 // Set to 0 to show suggestions immediately
                });

                // Trigger the autocomplete suggestions on input focus (click)
                $("#warehouse").on('focus', function() {
                    $(this).autocomplete('search', ''); // Force the dropdown to show on click/focus
                });

            });
        </script>


        <script>
            // Set up the CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



        </script>
    @endpush
</div>
@endsection
