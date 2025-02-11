@extends('frontend.layout.app')

@php
    use Carbon\Carbon;
//         Cache::forget('phistoryStaticsru');
//         Cache::forget('phistoryStaticsen');

       $phistoryStatics=Cache::get('phistoryStatics'.session()->get('locale'));

                 if($phistoryStatics===null) {
                     $data=[
                         'Date'=>$tr->translate('Date'),
                         'Payments'=>$tr->translate('Payments'),
                         'Amount'=>$tr->translate('Amount'),
                         'Full Name'=>$tr->translate('Full Name'),
                         'Approved'=>$tr->translate('Approved'),
                     ];
                     Cache::forever('phistoryStatics'.session()->get('locale'), $data);
                     $phistoryStatics=Cache::get('phistoryStatics'.session()->get('locale'));
                 }
   //

@endphp

@section('content')
    @push('style')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <style>
            .container {
                max-width: 1700px;
            }

            table, th, td {
                border: 1px solid;
            }

            td {
                padding: 7px;
            }


            .hideBr {
                display: none;
            }

            @media only screen and (max-width: 400px) {
                .hideBr {
                    display: block;
                }

                #bank_payment {
                    width: 250px !important;
                }

                #full_name {
                    width: 250px !important;
                }
            }
        </style>
    @endpush
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
             data-background="/frontendAssets/images/cargo.jpeg"
             style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{{Cache::get('dashboardStatics' . session()->get('locale'))['Payment History'] }}</h2>

            </div>
        </div>
    </section>

    <div class="container">
        @include('frontend.components.customer-nav-links')
        <div class="row justify-content-center">
            <div class="col-lg-8" style="overflow-x: auto;">
                <table id="myTable" class="display" style="width:100%">

                    <thead>
                    <tr class="text-center">
                        <th class="text-center">{{ $phistoryStatics['Date'] }}</th>
                        <th class="text-center">VIN</th>
                        <th class="text-center">{{ $phistoryStatics['Amount'] }}</th>
                        <th class="text-center">{{ $phistoryStatics['Full Name'] }}</th>
                        <th class="text-center">{{ $phistoryStatics['Approved'] }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($payment_report as $report)
                        <tr style="background-color: {{ $report->type === 'fill' ? '#82f98261' : '#ff898b61' }} ">
                            <td class="text-center">{{Carbon::parse($report->date)->format('d-m-Y') }}</td>
                            <td class="text-center">{{$report->car?->vin }}</td>
                            <td class="text-center">${{ $report->amount }}</td>
                            <td class="text-center"> {{ $report->full_name }}</td>
                            <td class="text-center">{!! $report->is_approved == 1 ? '<span class="true">YES</span>' : '<span class="false">NO</span>' !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <br>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
        <script>
            let table = new DataTable('#myTable');

            let text = document.querySelector('[for="dt-length-0"]');
            text.style.display='none';
        </script>
    @endpush

{{--    @push('scripts')--}}
{{--        <script>--}}
{{--            $(document).ready(function () {--}}
{{--                // Iterate over each .release_car_create_date element--}}
{{--                $(".release_car_create_date").each(function () {--}}
{{--                    // Get the input date string--}}
{{--                    var inputDateStr = $(this).val();--}}

{{--                    // Parse the input date string into a Date object--}}
{{--                    var inputDate = new Date(inputDateStr);--}}

{{--                    // Get the current date--}}
{{--                    var currentDate = new Date();--}}

{{--                    // Calculate the difference in milliseconds--}}
{{--                    var differenceMs = currentDate - inputDate;--}}

{{--                    // Convert milliseconds to days--}}
{{--                    var differenceDays = differenceMs / (1000 * 60 * 60 * 24);--}}

{{--                    if (differenceDays > 1) {--}}
{{--                        // Find the corresponding inputs and disable them--}}
{{--                        var $inputs = $(this).closest('.my-cars__td-relase-car-to-inputs').find('input');--}}
{{--                        $inputs.prop('disabled', true);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}


{{--            $('#bank_payment').on('change', function () {--}}
{{--                var current_value = $(this).val();--}}

{{--                $('#credit').text(current_value);--}}
{{--            })--}}


{{--            $('.amount').on('change', function () {--}}
{{--                var car_id = $(this).closest('td').find('.car_id').val();--}}
{{--                var csrf_token = $('meta[name="csrf-token"]').attr('content');--}}
{{--                var amount = $(this).closest('td').find('.amount').val();--}}

{{--                if (parseInt($(this).val()) <= parseInt($('#total_balance').text())) {--}}

{{--                    $.ajax({--}}
{{--                        url: "{{ route('customer.set_amount') }}", // Replace with your actual route name--}}
{{--                        method: 'POST',--}}
{{--                        data: {--}}
{{--                            car_id: car_id,--}}
{{--                            amount: amount--}}
{{--                        },--}}
{{--                        headers: {--}}
{{--                            'X-CSRF-TOKEN': csrf_token--}}
{{--                        },--}}

{{--                        success: function (data) {--}}
{{--                            var total = $('#total_balance').text()--}}
{{--                            $('#total_balance').text(total - amount)--}}

{{--                            if (data) {--}}
{{--                                alert("Amount Submited!!!");--}}
{{--                            }--}}

{{--                        }--}}
{{--                    });--}}
{{--                } else {--}}
{{--                    alert("Incorrect Amount")--}}
{{--                }--}}

{{--            });--}}


{{--            $('.save-release-information').on('click', function (e) {--}}
{{--                var name = $(this).closest('td').find('.release_to').val();--}}
{{--                var idNumber = $(this).closest('td').find('.id_number').val();--}}
{{--                var phone = $(this).closest('td').find('.release_phone').val();--}}
{{--                var car_id = $(this).closest('td').find('.car_id').val();--}}
{{--                var csrf_token = $('meta[name="csrf-token"]').attr('content');--}}
{{--                var my_cars__td_relase_car_to_inputs = $(this).closest('td').find('.my-cars__td-relase-car-to-inputs');--}}
{{--                var my_cars__td_relase_car_to_item = $(this).closest('td').find('.my-cars__td-relase-car-to-item');--}}
{{--                $.ajax({--}}
{{--                    url: "{{ route('saveRelease') }}", // Replace with your actual route name--}}
{{--                    method: 'POST',--}}
{{--                    data: {--}}
{{--                        car_id: car_id,--}}
{{--                        fname: name,--}}
{{--                        idnumber: idNumber,--}}
{{--                        phone: phone,--}}
{{--                    },--}}
{{--                    headers: {--}}
{{--                        'X-CSRF-TOKEN': csrf_token--}}
{{--                    },--}}

{{--                    success: function (data) {--}}
{{--                        if (data.message) {--}}
{{--                            my_cars__td_relase_car_to_inputs.fadeOut();--}}
{{--                            my_cars__td_relase_car_to_item.fadeIn();--}}
{{--                            my_cars__td_relase_car_to_item.text(name)--}}
{{--                        }--}}

{{--                    }--}}
{{--                });--}}
{{--            });--}}


{{--            $(document).on('click', '.my-cars__td-relase-car-to-item', function (e) {--}}
{{--                $(e.target).closest('tr').addClass('my-cars__tr-active');--}}
{{--                $(e.target).next().show();--}}
{{--                $(e.target).hide();--}}
{{--                $('.my-cars__td-cart-to-input:first', $(e.target).next()).val($(e.target).text());--}}
{{--            });--}}

{{--            async function copyVin($vin) {--}}
{{--                var textToCopy = $vin;--}}

{{--                try {--}}
{{--                    await navigator.clipboard.writeText(textToCopy);--}}
{{--                    alert('Text copied to clipboard: ' + textToCopy);--}}
{{--                } catch (err) {--}}
{{--                    console.error('Unable to copy text to clipboard', err);--}}
{{--                }--}}
{{--            }--}}


{{--            let table = new DataTable('#myTable', {--}}
{{--                search: {--}}
{{--                    return: true--}}
{{--                },--}}
{{--                columnDefs: [{--}}
{{--                    width: 100,--}}
{{--                    targets: 0--}}
{{--                }, {--}}
{{--                    width: 100,--}}
{{--                    targets: 1--}}
{{--                }, {--}}
{{--                    width: 100,--}}
{{--                    targets: 2--}}
{{--                }, {--}}
{{--                    width: 70,--}}
{{--                    targets: 3--}}
{{--                }, {--}}
{{--                    width: 50,--}}
{{--                    targets: 4--}}
{{--                }],--}}
{{--                order: [--}}
{{--                    [0, 'desc']--}}
{{--                ],--}}

{{--                scrollX: true--}}
{{--            });--}}

{{--            $('#dt-search-0').keyup(function () {--}}
{{--                // Get the value of the search input--}}
{{--                var searchText = $(this).val();--}}

{{--                // Perform search on DataTable--}}
{{--                table.search(searchText).draw();--}}
{{--            });--}}
{{--        </script>--}}
{{--    @endpush--}}
@endsection
