@extends('frontend.layout.app')

@section('content')
    @push('style')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
        <style>
            .container {
                max-width: 1700px;
            }
        </style>
    @endpush
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/assets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{!! $tr->translate('Car list') !!}</h2>
            </div>
        </div>
    </section>

    <div class="container">

        <ul class="tabs">
            <li class="tabs__item ">
                <a href="{{ route('customer.dashboard') }}"
                    class="{{ request()->routeIs('customer.dashboard') || request()->routeIs('customer.archivedcars') ? 'active' : '' }}">{!! $tr->translate('My cars') !!}</a>
            </li>
            @if (!auth()->user()->hasRole('portmanager'))
                <li class="tabs__item tabs__item_active ">
                    <a href="{{ route('customer.payment_registration') }}"
                        class="{{ request()->routeIs('customer.payment_registration') ? 'active' : '' }}">{!! $tr->translate('My Payments') !!}</a>
                </li>
            @endif
        </ul>

        <div class="d-flex gap-3">
            <li class="tabs__item ">
                <a href="{{ route('customer.dashboard') }}"
                    class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">{!! $tr->translate('Active Cars') !!}</a>
            </li>
            @if (!auth()->user()->hasRole('portmanager'))
                <li class="tabs__item ">
                    <a href="{{ route('customer.archivedcars') }}"
                        class="{{ request()->routeIs('customer.archivedcars') ? 'active' : '' }}">{!! $tr->translate('Cars History') !!}</a>
                </li>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-12">
                <br>
                <table id="myTable" class="display">
                    <thead>
                        <tr>
                            <th>{!! $tr->translate('Date') !!}</th>
                            {{-- <th>{!! $tr->translate('Year') !!}</th> --}}
                            <th>{!! $tr->translate('Make/Model/Year') !!}</th>
                            {{-- <th>{!! $tr->translate('Model') !!}</th> --}}
                            <th>{!! $tr->translate('VIN') !!}</th>
                            <th>{!! $tr->translate('E/H') !!}</th>
                            <th>{!! $tr->translate('Title Recived') !!}</th>
                            <th>{!! $tr->translate('Key') !!}</th>
                            <th>{!! $tr->translate('From') !!}</th>
                            {{-- <th>{!! $tr->translate('To') !!}</th> --}}
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th>{!! $tr->translate('Release car to') !!}</th>
                            @endif

                            @if (!auth()->user()->hasRole('portmanager'))
                                @if (session()->get('auth')->parent_of <= 0)
                                    <th>{!! $tr->translate('Assing car to') !!}</th>
                                @else
                                    <th></th>
                                @endif
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th>{!! $tr->translate('All Cost') !!}</th>
                                <th>{!! $tr->translate('Recived') !!}</th>
                            @endif

                            <th>{!! $tr->translate('Days') !!}</th>
                            <th>{!! $tr->translate('Container') !!}</th>
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th>{!! $tr->translate('Amount due') !!}</th>
                            @endif
                            @if (!auth()->user()->hasRole('portmanager'))
                                <th></th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cars as $key => $car)
                            @php
                                $createdDate = \Carbon\Carbon::parse($car->created_at);
                                $currentDate = \Carbon\Carbon::now();
                                $differenceInDays = $createdDate->diffInDays($currentDate);

                                if ((isset($balance) && $balance <= 0) || $car->balance <= 0) {
                                    $color = '#82f98261';
                                } else {
                                    $color = $car->color;
                                }
                            @endphp
                            <tr style="background-color: {{ $color }};">
                                <td>{{ $car->created_at->format('Y-m-d') }}</td>
                                {{-- <td>{{ $car->year }}</td> --}}
                                <td>{{ $car->make }}</td>
                                {{-- <td>{{ $car->model }}</td> --}}
                                <td style="align-items: center;gap: 5px;">
                                    <a style="color: #1a7fca;font-size: 15px;"
                                        href="/dashboard/car-info/{{ $car->vin }}"> {!! $car->getMedia()->first() ? '📷 ' . $car->vin : $car->vin !!} </a>

                                    <span class="p-cursor" onclick="copyVin('{{ $car->vin }}');"><img width="20px"
                                            src="https://vsbrothers.com/img/copy_icon.svg"></span>
                                </td>
                                <td><img class="fuel_type"
                                        src="{{ $car->fuel == 'Petrol' ? asset('assets/fuel_gas.svg') : asset('assets/fuel_hybrid.svg') }}"
                                        alt="fuel"></td>
                                <td>{!! $car->title_recived == 1 ? '<span class="true">YES</span>' : '<span class="false">NO</span>' !!}</td>
                                {{-- <td>{!! $car->cargo_recived == 1 ? '<span class="true">YES</span>' : '<span class="false">NO</span>' !!}</td> --}}
                                <td>{!! $car->key == 1 ? '<span class="true">YES</span>' : '<span class="false">NO</span>' !!}</td>
                                <td>{{ isset($car->state->name) ? $car->state->name : '' }}</td>
                                {{-- <td>{{ $car->toPort }}</td> --}}
                                @if (!auth()->user()->hasRole('portmanager'))
                                    <td>
                                        <div class="my-cars__td-relase-car-to-item green  " style="">
                                            {{ $car->release_car_name }}</div>
                                        <ul class="my-cars__td-relase-car-to-inputs" style="display: none;">
                                            <li
                                                class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_user">
                                                <input class="release_to form-control" name="fname"
                                                    placeholder="Full name" value="{{ $car->release_car_name }}">
                                                <input type="hidden" class="car_id" name="car_id"
                                                    value="{{ $car->id }}">
                                                <input type="hidden" class="release_car_create_date"
                                                    name="release_car_create_date"
                                                    value="{{ $car->release_car_create_date }}">
                                            </li>

                                            <li
                                                class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_id">
                                                <input class="id_number form-control" name="idnumber"
                                                    placeholder="ID Number" value="{{ $car->release_car_idnumber }}">
                                            </li>

                                            <li
                                                class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_phone">
                                                <input class="release_phone form-control" name="phone" placeholder="Phone"
                                                    value="{{ $car->release_car_phone }}">
                                            </li>
                                            <li class="my-cars__td-relase-car-to-inputs-item">
                                                <button class="btn btn-primary save-release-information" type="button"
                                                    data-behavior="save-release-information">Save</button>
                                            </li>
                                        </ul>
                                    </td>
                                @endif
                                @if (!auth()->user()->hasRole('portmanager'))
                                    @if (session()->get('auth')->parent_of <= 0)
                                        <td>
                                            <input type="hidden" name="car_id" class="car_id"
                                                value="{{ $car->id }}">
                                            <select name="team_id" class="form-control team_id" id="team_id">
                                                <option value=""></option>
                                                @foreach ($teams as $team)
                                                    <option value="{{ $team->id }}"
                                                        {{ $car->team_id == $team->id ? 'selected' : '' }}>
                                                        {{ $team->contact_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @endif
                                @if (session()->get('auth')->parent_of <= 0)
                                    @if (!auth()->user()->hasRole('portmanager'))
                                        @if (!empty($car->percent))
                                            <td><span style="color: #e82121">${{ $car->debit }}</span></td>
                                        @else
                                            <td>${{ $car->debit }}</td>
                                        @endif
                                        <td>
                                            ${{ $car->recived }}
                                        </td>
                                    @endif

                                    <td>{{ $differenceInDays }}</td>
                                    <td>{{ $car->container }}</td>
                                    @if (!auth()->user()->hasRole('portmanager'))
                                        @if (!empty($car->percent))
                                            <td>
                                                <div class="my-cars__td-balance green"
                                                    style="color: #e82121;display: flex;align-items: center;flex-wrap: wrap;flex-direction: column;">
                                                    ${{ $car->balance }}

                                                    {{-- @if (!empty($car->old_amount_due))
                                                        <img class="arrowdown" src="/assets/arrow-select_down.svg"
                                                            alt="arrow">
                                                    @endif --}}
                                                    @if ($car->percent)
                                                        <button type="button" class="btn btn-primary open-modal"
                                                            data-id="{{ $car->id }}"
                                                            data-car_info="{{ $car->vin . ' ' . $car->make }}">
                                                            CREDIT
                                                        </button>
                                                    @endif
                                                </div>
                                                @if (!empty($car->old_amount_due))
                                                    <ul class="my-cars__td-balance-to-inputs"
                                                        style="font-size:14px;display: none;">
                                                        @php
                                                            $originalBalance = $car->debit - $car->recived;
                                                            $percent = $car->percent; // This should be in decimal form, i.e., 5% should be 0.05.

                                                            if (isset($car->extra_price) && $car->extra_price > 0) {
                                                                $percentAmount =
                                                                    (int) $car->extra_price * ($percent / 100);
                                                            } else {
                                                                $percentAmount =
                                                                    (int) $originalBalance * ($percent / 100);
                                                            }

                                                            // Assuming 30 days for simplicity.
                                                            $days = 30;

                                                            // Calculate the daily charge.
                                                            $dailyCharge =
                                                                (int) number_format($percentAmount, 2, '.', '') / $days;

                                                            $newAmountDue = $dailyCharge * $differenceInDays;
                                                        @endphp
                                                        Begin Amount due:
                                                        ${{ isset($car->extra_price) && $car->extra_price > 0 ? $car->extra_price : $originalBalance }}
                                                        <br>
                                                        Financed fee:
                                                        ${{ number_format($dailyCharge * $differenceInDays, 2) }}<br>
                                                        Percent: {{ $car->percent }}% <br>

                                                    </ul>
                                                @endif
                                            </td>
                                        @else
                                            <td>${{ $car->balance }}</td>
                                        @endif
                                    @endif
                                @else
                                    <td>{{ $car->invoice_debit }}</td>
                                    <td>{{ $car->invoice_received }}</td>
                                    <td>{{ $differenceInDays }}</td>
                                    <td>{{ $car->container }}</td>
                                    <td>{{ $car->invoice_balance }}</td>
                                @endif
                                @if (!auth()->user()->hasRole('portmanager'))
                                    <td>

                                        <div class="row gap-1">
                                            <form action="{{ route('customer.generate_invoice') }}" method="POST"
                                                target="_blank">
                                                @csrf
                                                <input type="hidden" name="car_id" value="{{ $car->id }}">
                                                <button type="submit" class="btn btn-secondary">

                                                    {!! $tr->translate('Invoice') !!}
                                                </button>
                                            </form>
                                            @if (session()->get('auth')->parent_of <= 0)
                                                @if (count($teams) > 0)
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#exampleModal{{ $key }}">
                                                        {!! $tr->translate('Send Invoice') !!}
                                                    </button>
                                                @endif
                                            @endif
                                        </div>

                                    </td>
                                @endif

                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{ $key }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Send Invoice</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('customer.addInvoicePrice') }}" method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $car->id }}" name="car_id">

                                                <div class="form-group">
                                                    <label for="">All Cost</label>
                                                    <input type="number" class="form-control" name="invoice_debit"
                                                        value="">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Recived</label>

                                                    <input type="number" class="form-control" name="invoice_received"
                                                        value="">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Amount due</label>
                                                    <input type="number" class="form-control" name="invoice_balance"
                                                        value="">
                                                </div>

                                                <button type="submit" class="btn btn-success mt-2">Send</button>

                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </tbody>

                </table>



                <br>

            </div>
        </div>
    </div>

    @push('scripts')
        {{ $html->scripts() }}



        <script>
            $(document).ready(function() {
                // Iterate over each .release_car_create_date element
                $(".release_car_create_date").each(function() {
                    // Get the input date string
                    var inputDateStr = $(this).val();

                    // Parse the input date string into a Date object
                    var inputDate = new Date(inputDateStr);

                    // Get the current date
                    var currentDate = new Date();

                    // Calculate the difference in milliseconds
                    var differenceMs = currentDate - inputDate;

                    // Convert milliseconds to days
                    var differenceDays = differenceMs / (1000 * 60 * 60 * 24);

                    if (differenceDays > 3) {
                        // Find the corresponding inputs and disable them
                        var $inputs = $(this).closest('.my-cars__td-relase-car-to-inputs').find('input');
                        $inputs.prop('disabled', true);
                    }
                });
            });


            $('.team_id').on('change', function(e) {
                var car_id = $(this).closest('td').find('.car_id').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var team_id = $(this).val();
                var team_text = $(this).find("option:selected").text();
                var confirmed = window.confirm('Are you agreeing to assign a car to ' + team_text.trim() + ' ?');

                if (!confirmed) {
                    // If the user cancels, return early without making the AJAX request
                    $(this).val(''); // Deselect the selected value
                    return;
                }
                $.ajax({
                    url: "{{ route('customer.addTeamToCar') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        car_id: car_id,
                        team_id: team_id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function(data) {
                        if (data.message) {
                            location.reload();
                        }

                    }
                });
            });

            $('.save-release-information').on('click', function(e) {
                var name = $(this).closest('td').find('.release_to').val();
                var idNumber = $(this).closest('td').find('.id_number').val();
                var phone = $(this).closest('td').find('.release_phone').val();
                var car_id = $(this).closest('td').find('.car_id').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var my_cars__td_relase_car_to_inputs = $(this).closest('td').find('.my-cars__td-relase-car-to-inputs');
                var my_cars__td_relase_car_to_item = $(this).closest('td').find('.my-cars__td-relase-car-to-item');
                $.ajax({
                    url: "{{ route('saveRelease') }}", // Replace with your actual route name
                    method: 'POST',
                    data: {
                        car_id: car_id,
                        fname: name,
                        idnumber: idNumber,
                        phone: phone,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },

                    success: function(data) {

                        my_cars__td_relase_car_to_inputs.fadeOut();
                        my_cars__td_relase_car_to_item.fadeIn();
                        my_cars__td_relase_car_to_item.text(name)

                    }
                });
            });



            $(document).on('click', '.my-cars__td-relase-car-to-item', function(e) {
                $(e.target).closest('tr').addClass('my-cars__tr-active');
                $(e.target).next().show();
                $(e.target).hide();

                $('.my-cars__td-cart-to-input:first', $(e.target).next()).val($(e.target).text());
            });

            // $(document).on('click', '.my-cars__td-balance', function(e) {
            //     $(e.target).closest('tr').addClass('my-cars__tr-active');
            //     $(e.target).next().show();
            //     $(e.target).find('.arrowdown').hide();
            //     // $(e.target).hide();
            //     $('.my-cars__td-balance-to-inputs:first', $(e.target).next()).val($(e.target).text());
            // });

            // $(document).on('click', '.my-cars__td-balance-to-inputs', function(e) {
            //     $(e.target).prev().show();
            //     $(e.target).prev().find('.arrowdown').show();
            //     $(e.target).hide();
            // });

            $(document).ready(function() {
                $('.arrowdown').on('click', function() {
                    const $arrowDown = $(this);
                    const $balanceInputs = $arrowDown.closest('tr').find('.my-cars__td-balance-to-inputs');

                    $balanceInputs.toggle();
                    $arrowDown.toggleClass('active');
                });
            });



            async function copyVin($vin) {
                var textToCopy = $vin;

                try {
                    await navigator.clipboard.writeText(textToCopy);
                    alert('Text copied to clipboard: ' + textToCopy);
                } catch (err) {
                    console.error('Unable to copy text to clipboard', err);
                }
            }
        </script>

        @if (auth()->user()->hasRole('portmanager'))
            <script>
                let table = new DataTable('#myTable', {

                    drawCallback: function(settings) {
                        $('#myTable tbody tr').hide();
                    },
                    columnDefs: [{
                        width: 65,
                        targets: 0
                    }, {
                        width: 180,
                        targets: 2
                    }, {
                        width: 150,
                        targets: 11
                    }, {
                        width: {{ session()->get('auth')->parent_of <= 0 ? 70 : 0 }},
                        targets: 12
                    }],
                    order: [
                        [0, 'desc']
                    ],
                    scrollX: true,
                    searching: true,
                    bFilter: true, // Enable search/filter
                    bPaginate: false, // Disable pagination
                });

                $('#dt-search-0').keyup(function() {
                    // Show matching rows on search
                    var searchTerm = $(this).val().trim().toLowerCase(); // Trim whitespace and lowercase

                    if (searchTerm.length >= 5 && searchTerm.trim() !== "") {
                        // Perform filtering if criteria are met
                        $('#myTable tbody tr').each(function() {
                            var textContent = $(this).text().toLowerCase();

                            if (textContent.indexOf(searchTerm) !== -1) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    }



                });
            </script>
        @else
            <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css">
            <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js" type="text/javascript">
            </script>

            <style>
                .datatable-container {
                    position: relative;
                }

                .datatable-container:after {
                    content: "\21C6";
                    /* Unicode for a right arrow icon */
                    position: absolute;
                    top: 50%;
                    right: 10px;
                    transform: translateY(-50%);
                    font-size: 20px;
                    color: #999;
                    opacity: 0.5;
                    pointer-events: none;
                }

                .datatable-container::-webkit-scrollbar-horizontal {
                    display: none;
                    /* Hide the default scrollbar */
                }
            </style>

            <script>
                const dataTable = document.querySelector('#myTable');
                const scrollIcon = document.createElement('div');
                scrollIcon.classList.add('scroll-icon');
                scrollIcon.innerHTML = '&#8594;'; // Right arrow icon

                dataTable.parentNode.insertBefore(scrollIcon, dataTable.nextSibling);

                dataTable.addEventListener('scroll', function() {
                    if (dataTable.scrollWidth > dataTable.clientWidth) {
                        scrollIcon.style.display = 'block';
                    } else {
                        scrollIcon.style.display = 'none';
                    }
                });
            </script>

            <script>
                let table = new DataTable('#myTable', {
                    iDisplayLength: 50,
                    scrollX: true,
                    columnDefs: [{
                            width: 65,
                            targets: 0
                        }, {
                            width: 180,
                            targets: 2
                        }, {
                            width: 130,
                            targets: 6
                        }, {
                            width: 100,
                            targets: 7
                        }, {
                            width: 100,
                            targets: 8
                        }, {
                            width: 100,
                            targets: 9
                        },
                        {
                            width: 100,
                            targets: 13
                        }, {
                            width: {{ session()->get('auth')->parent_of <= 0 ? 70 : 0 }},
                            targets: 14
                        }
                    ],
                    fixedHeader: {
                        header: true,
                        headerOffset: 123,
                        footer: true
                    },
                    order: [
                        [0, 'desc']
                    ],
                });
            </script>
        @endif
    @endpush
@endsection
