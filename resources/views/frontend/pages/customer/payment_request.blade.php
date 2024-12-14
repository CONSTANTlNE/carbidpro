@extends('layout.app')

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
                    class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">{!! $tr->translate('My cars') !!}</a>
            </li>
            <li class="tabs__item tabs__item_active ">
                <a href="{{ route('customer.payment_registration') }}"
                    class="{{ request()->routeIs('customer.payment_registration') || request()->routeIs('customer.payment_history') ? 'active' : '' }}">{!! $tr->translate('My Payments') !!}</a>
            </li>

        </ul>




        <div class="d-flex gap-3">
            <li class="tabs__item ">
                <a href="{{ route('customer.payment_registration') }}"
                    class="{{ request()->routeIs('customer.payment_registration') ? 'active' : '' }}">{!! $tr->translate('Active Payments') !!}</a>
            </li>
            <li class="tabs__item ">
                <a href="{{ route('customer.payment_history') }}"
                    class="{{ request()->routeIs('customer.payment_history') ? 'active' : '' }}">{!! $tr->translate('Payments History') !!}</a>
            </li>
        </div>

        <div class="row">
            <div class="col-lg-7">
                @if (session('success'))
                    <div class="alert alert-success mt-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('customer.payment_registration_submit') }}">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="bank_payment">{!! $tr->translate('Bank payment') !!}</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" pattern="[0-9]*" name="bank_payment" class="form-control"
                                        id="bank_payment" aria-label="Bank payment"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="full_name">{!! $tr->translate('Full name') !!}</label>
                                <input type="text" name="full_name" class="form-control" id="full_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="payment_date">{!! $tr->translate('Date') !!}</label>
                                <input type="date" name="payment_date" class="form-control" id="payment_date">
                            </div>
                        </div>
                        <div class="d-flex col align-items-baseline gap-2">
                            <button class="btn btn-success" type="submit" style="margin-top: 30px;">
                                {!! $tr->translate('Submit') !!}
                            </button>
                            <div>{!! $tr->translate('Credit') !!}: $<span id="credit">0.0</span></div>

                            <input type="hidden" value="" name="balance">
                        </div>

                    </div>

                </form>
            </div>
            <div class="col-lg-5 pt-5">
                <div class="balance d-flex" style="flex-direction: column;">
                    <div>
                        {!! $tr->translate('Total credit') !!}: $<span id="total_balance">{{ isset($balance) ? $balance : 0 }}</span>
                    </div>
                    @if (isset($pending) && $pending != 0)
                        <div style="color:blue;opacity: 0.6;">
                            {!! $tr->translate('Pending') !!}: $<span id="pending">{{ isset($pending) ? $pending : 0 }}</span>
                        </div>
                    @endif


                </div>
            </div>
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
                            <th>{!! $tr->translate('Release car to') !!}</th>
                            <th>{!! $tr->translate('All Cost') !!}</th>
                            <th>{!! $tr->translate('Recived') !!}</th>
                            <th>{!! $tr->translate('Days') !!}</th>
                            <th>{!! $tr->translate('Container') !!}</th>
                            <th>{!! $tr->translate('Amount due') !!}</th>
                            <th>{!! $tr->translate('Your amount') !!}</th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cars as $car)
                            @php
                                $createdDate = \Carbon\Carbon::parse($car->created_at);
                                $currentDate = \Carbon\Carbon::now();
                                $differenceInDays = $createdDate->diffInDays($currentDate);

                                if (isset($balance) && $balance <= 0 && $car->balance <= 0) {
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
                                <td>
                                    <div class="my-cars__td-relase-car-to-item green  " style="">
                                        {{ $car->release_car_name }}</div>
                                    <ul class="my-cars__td-relase-car-to-inputs" style="display: none;">
                                        <li
                                            class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_user">
                                            <input class="release_to form-control" name="fname" placeholder="Full name"
                                                value="{{ $car->release_car_name }}">
                                            <input type="hidden" class="car_id" name="car_id"
                                                value="{{ $car->id }}">
                                            <input type="hidden" class="release_car_create_date"
                                                name="release_car_create_date"
                                                value="{{ $car->release_car_create_date }}">
                                        </li>

                                        <li
                                            class="mb-2 my-cars__td-relase-car-to-inputs-item my-cars__td-relase-car-to-inputs-item_id">
                                            <input class="id_number form-control" name="idnumber" placeholder="ID Number"
                                                value="{{ $car->release_car_idnumber }}">
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
                                @if (session()->get('auth')->parent_of <= 0)
                                    <td>
                                        ${{ $car->debit }}
                                    </td>
                                    <td>
                                        ${{ $car->recived }}
                                    </td>
                                    <td>{{ $differenceInDays }}</td>
                                    <td>{{ $car->container }}</td>


                                    <td>${{ $car->balance }}

                                        @if ($car->percent)
                                            <br>
                                            <button type="button" class="btn btn-primary open-modal"
                                                data-id="{{ $car->id }}"
                                                data-car_info="{{ $car->vin . ' ' . $car->make }}">
                                                CREDIT
                                            </button>
                                        @endif
                                    </td>
                                @else
                                    {{-- <td>
                                        @foreach ($car->balance_accounting as $balance_accounting)
                                            @if ($balance_accounting['name'] == 'Vehicle cost')
                                                ${{ $balance_accounting['value'] }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($car->balance_accounting as $balance_accounting)
                                            @if ($balance_accounting['name'] == 'Payed')
                                                ${{ $balance_accounting['value'] }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $differenceInDays }}</td>
                                    <td>{{ $car->container }}</td>
                                    <td>${{ $car->invoice_balance }}</td> --}}
                                    <td>{{ $car->invoice_debit }}</td>
                                    <td>{{ $car->invoice_received }}</td>
                                    <td>{{ $differenceInDays }}</td>
                                    <td>{{ $car->container }}</td>
                                    <td>{{ $car->invoice_balance }}</td>
                                @endif
                                <td>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="hidden" class="car_id" name="car_id"
                                            value="{{ $car->id }}">

                                        <input type="hidden" class="amount_due" name="amount_due"
                                            value="{{ $car->balance }}">

                                        <input type="number" min="0" name="amount" class="form-control amount"
                                            aria-label="Amount"
                                            {{ (isset($balance) && $balance <= 0) || $car->balance <= 0 ? 'disabled' : '' }}>

                                        <button type="button" class="btn btn-success submit_amount">Submit</button>
                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div style="text-align:right;color:red;margin-right:20px;font-size:18px">{!! $tr->translate('Total Amount due') !!}: $<span
                        id="total_balance">{{ isset($total_amount_due) ? $total_amount_due : 0 }}</span>
                </div>


                <br>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelector('.amount').addEventListener('keydown', function(e) {
                if (e.key === '-' || e.key === '+' || e.key === 'e') {
                    e.preventDefault(); // Prevents the entry of symbols
                }
            });

            
            let table = new DataTable('#myTable', {
                pageLength: 50,
                columnDefs: [{
                        width: 65,
                        targets: 0
                    }, {
                        width: 180,
                        targets: 2
                    },
                    {
                        width: 70,
                        targets: 6
                    },
                    {
                        width: 100,
                        targets: 8
                    },
                    {
                        width: 180,
                        targets: 9
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                scrollX: true,
            });
        </script>
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

                    if (differenceDays > 1) {
                        // Find the corresponding inputs and disable them
                        var $inputs = $(this).closest('.my-cars__td-relase-car-to-inputs').find('input');
                        $inputs.prop('disabled', true);
                    }
                });
            });

            // Get today's date
            var today = new Date();

            // Format the date to YYYY-MM-DD (required by input type="date")
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();

            var formattedDate = yyyy + '-' + mm + '-' + dd;

            // Set the value of the input field to today's date
            document.getElementById('payment_date').value = formattedDate;



            $('#bank_payment').on('change', function() {
                var current_value = $(this).val();

                $('#credit').text(current_value);
            })


            $('.submit_amount').on('click', function() {
                var car_id = $(this).closest('td').find('.car_id').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var amount = $(this).closest('td').find('.amount').val();
                var due = $(this).closest('td').find('.amount_due').val();


                if (parseInt(amount) <= parseInt($('#total_balance').text()) && parseInt(amount) != 0 && parseInt(
                        amount) <= parseInt(due)) {

                    var $this = $(this);
                    $this.attr("disabled", true);

                    $.ajax({
                        url: "{{ route('customer.set_amount') }}", // Replace with your actual route name
                        method: 'POST',
                        data: {
                            car_id: car_id,
                            amount: amount
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrf_token
                        },

                        success: function(data) {
                            var total = $('#total_balance').text()
                            $('#total_balance').text(total - amount)

                            if (data) {
                                alert("Amount Submited!!!");
                                location.reload();
                            }

                        }

                    });

                } else {
                    alert("Incorrect Amount")
                }

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
                        if (data.message) {
                            my_cars__td_relase_car_to_inputs.fadeOut();
                            my_cars__td_relase_car_to_item.fadeIn();
                            my_cars__td_relase_car_to_item.text(name)
                        }

                    }
                });
            });



            $(document).on('click', '.my-cars__td-relase-car-to-item', function(e) {
                $(e.target).closest('tr').addClass('my-cars__tr-active');
                $(e.target).next().show();
                $(e.target).hide();
                $('.my-cars__td-cart-to-input:first', $(e.target).next()).val($(e.target).text());
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
    @endpush
@endsection
