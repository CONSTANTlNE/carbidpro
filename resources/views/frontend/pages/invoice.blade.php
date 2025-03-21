<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="ThemeMarch">
    <!-- Site Title -->
    <title>Invoice{{$invNumber}}</title>
    <link rel="stylesheet" href="{{asset('invoiceAssets/assets/css/style.css')}}">
    <style>
        #mobilebanks {
            display: none;
        }

        p {
            margin-bottom: 2px !important;
        }

        td {
            padding: 2px !important;
            border-bottom: 1px solid black !important;
            border-top: 1px solid black !important;

        }

        th {
            font-weight: bolder !important;
        }

        .cs-style2 {
            border-top: 1px solid black !important;
            border-right: 1px solid black !important;
            border-left: 1px solid black !important;
        }


        @media (max-width: 500px) {
            /* Adjust the breakpoint as needed */
            #desktopBanks {
                display: none;
            }

            #image {
                display: none;
            }

            .width100 {
                width: 100% !important;
            }

            #mobilebanks {
                display: flex;
            }

            #totals {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
<div class="cs-container " style="padding: 0!important;">
    <div style="padding: 5px" class="cs-bg-white cs-border-radious25 ">
        <div class="cs-bottom-bg">
            <div class="cs-top-bg">
                {{--                cs-p-25-50--}}
                <div style="padding: 0!important" class="cs-invoice cs-style1 cs-no_border  cs-bg-none">
                    <div>
                        <div class="cs-invoice_in" id="download_section">
                            <div class="display-flex space-between cs-type1 cs-mb10 cs-no_border flex-wrap">
                                <div class="display-flex align-item-center flex-wrap">
                                    <div class="cs-logo cs-mr10  cs">
                                        <img style="height: 50px"
                                             src="{{asset('invoiceAssets/assets/img/carbidlogo.jpeg')}}" alt="Logo">
                                    </div>
                                </div>
                                <div class="cs-invoice_left">
                                    <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b
                                                class="cs-primary_color">Invoice
                                            No:</b> #{{$invNumber}}</p>
                                    <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16">
                                        <b
                                                class="cs-primary_color ">Date
                                            :</b> {{now()->format('m-d-Y')}}</p>
                                </div>

                            </div>
                            <div class="display-flex cs-mb5 space-between flex-wrap">
                                <div class="cs-invoice_left cs-width_8">
                                    <b class="cs-primary_color">Company:</b>
                                    <p>
                                        CarBidPro LLC
                                        <br>
                                        ID: 405698963
                                        <br>
                                        www.carbidpro.com
                                    </p>
                                </div>
                                <div class="cs-invoice_right">
                                    <b class="cs-primary_color">Customer:</b>
                                    <p>
                                        Name: <span
                                                contenteditable="true" style="background: yellow" class="backgrounds">{{ $customer->company_name ?: $user->contact_name }}</span>
                                        <br>
                                        ID : <span contenteditable="true" style="background: yellow" class="backgrounds"> {{$customer->personal_number}}</span>
                                        <br>

                                    </p>
                                </div>
                            </div>
                            {{--COSTS TABLE--}}
                            <div class="cs-table cs-style2 padding-rignt-left">
                                <div class="tm-border-none">
                                    <div class="cs-table_responsive">
                                        <table class="cs-mb10">
                                            <div class="tm-border-1px"></div>
                                            <thead class="border-bottom-1 cs-mb50">
                                            <tr class="cs-secondary_color">
                                                <th style="padding-left: 5px" class="cs-width_5 cs-normal p-2">DESCRIPTION</th>

                                                <th style="padding-right: 5px" class="cs-width_2 cs-normal cs-text_right">AMOUNT</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($costs as $key =>$amount)
                                                <tr class="border-bottom-1">
                                                    <td style="padding-left: 5px!important" class="cs-width_5 cs-primary_color cs-f15">
                                                        {{$amount['name']}} {{$amount['name']=='Vehicle cost' ? ' VIN : ' . $car->vin : ''}}
                                                    </td>
                                                    <td  style="padding-right: 5px!important"
                                                            class=" cs-width_2 cs-text_right cs-primary_color cs-f15">$
                                                        <input style="all: unset;max-width: 50px;background: yellow" contenteditable="true"
                                                               class="amounts backgrounds" value="{{$amount['value']}}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if($totalinterest)
                                                <tr class="border-bottom-1">
                                                    <td class="cs-width_5 cs-primary_color cs-f15">
                                                        Credit Fee
                                                    </td>
                                                    <td
                                                            class=" cs-width_2 cs-text_right cs-primary_color cs-f15">$
                                                        <input style="all: unset;max-width: 50px;background: yellow" contenteditable="true"
                                                               class="amounts backgrounds" value="{{$totalinterest}}">
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{--IBAN AND TOTALS--}}
                            <div class="display-flex space-between  ">

                                <div id="desktopBanks" class="cs-width_5 ">
                                    <p class="cs-secondary_color cs-text_left cs-f15">Bank of Georgia Swift:
                                        <span class="cs-ml10 cs-primary_color cs-semi_bold" style="font-size:12px ">BAGAGE22</span>
                                    </p>
                                    <p class="cs-secondary_color cs-text_left cs-f15">IBAN Account:
                                        <span class=" cs-ml10 cs-primary_color cs-semi_bold" style="font-size:12px ">GE82BG0000000590659106</span>
                                    </p>
                                    {{--                                    <div class="cs-border-50percent cs-mb5"></div>--}}
                                    <p class="cs-secondary_color cs-text_left cs-f15">JSC TBC Bank SWIFT:
                                        <span class="cs-ml10 cs-primary_color cs-semi_bold" style="font-size:12px ">TBCBGE22</span>
                                    </p>
                                    <p class="cs-secondary_color cs-text_left cs-f15">IBAN Account:
                                        <span class=" cs-ml10 cs-primary_color cs-semi_bold" style="font-size:12px ">GE05TB7081136120100010</span>
                                    </p>
                                </div>

                                <div id="totals" class="cs-width_4">
                                    <p class="cs-secondary_color cs-text_right cs-f15">Total: <span
                                                id="total" class="cs-ml30 cs-primary_color cs-semi_bold">636.4</span>
                                    </p>
                                    <p class="cs-secondary_color cs-text_right cs-f15 backgrounds">Paid:
                                        <input id="payment"
                                               style="all:unset;max-width: 50px;color: black;font-weight: bold;margin-left: 25px;background: yellow"
                                               value="${{-$payment}}">
                                    </p>
                                    {{--                                    <div class="cs-border-50percent"></div>--}}
                                    <p class="cs-secondary_color cs-text_right cs-f15 cs-mt15">Amount Due:
                                        <span
                                                id="amountDue" class="cs-ml30 cs-primary_color cs-semi_bold">0.00</span>
                                    </p>
                                    {{--                                    <div class="cs-border-50percent"></div>--}}
                                </div>
                            </div>

                            <div id="mobilebanks" class="display-flex space-between ">

                                <div class="cs-width_5 width100">
                                    <p class="cs-secondary_color cs-text_left cs-f15">Bank of Georgia Swift:
                                        <span class="cs-ml30 cs-primary_color cs-semi_bold">BAGAGE22</span>
                                    </p>
                                    <p class="cs-secondary_color cs-text_left cs-f15">IBAN Account:
                                        <span class=" cs-ml10 cs-primary_color cs-semi_bold">GE82BG0000000590659106</span>
                                    </p>
                                    <div class="cs-border-50percent cs-mb5"></div>
                                    <p class="cs-secondary_color cs-text_left cs-f15">JSC TBC Bank SWIFT:
                                        <span class="cs-ml30 cs-primary_color cs-semi_bold">TBCBGE22</span>
                                    </p>
                                    <p class="cs-secondary_color cs-text_left cs-f15">IBAN Account:
                                        <span class=" cs-ml10 cs-primary_color cs-semi_bold">GE05TB7081136120100010</span>
                                    </p>
                                </div>
                            </div>
                            <div class="display-flex justify-content-center cs-mb5">
                                <b>საფუძველი / Description</b>
                            </div>

                            <p style="font-size: 12px;text-align: center"> საქართველოს ბანკი: <strong> ამერიკის აუქციონზე შეძენილი ავტომობილის ღირებულება</strong> </p>
                            <p style="font-size: 12px;text-align: center;margin-bottom: 10px!important"> თიბისი:  <strong>  სავალუტო ბაზარზე შეძენილი უცხოური ვალუტის შეტანა/გადარიცხვა </strong>   </p>


                            {{--AGREEMENT GEO--}}
                            <div class="display-flex justify-content-center cs-mb5 ">
                                <b>შეთანხმება / Agreement</b>
                            </div>
                            <div class="display-flex space-between cs-mb10">
                                <p style="font-size: 10px;text-align: justify">
                                    მას შემდეგ, რაც წინამდებარე ინვოისის მიხედვით დამკვეთი განახორციელებს თანხის
                                    შემოტანას შპს ქარბიდპრო-ს ანგარიშზე, ჩაითვლება, რომ ის ეთანხმება ინვოისში მითითებულ
                                    ყველა პირობას. მათ შორის, ინვოისი წარმოადგენს მხარეთა შორის შეთანხმებას, თუ რა სახის
                                    მომსახურებას გაუწევს შემსრულებელი დამკვეთს.
                                    <br> <br>
                                    <b>მომსახურების საგანი:</b>
                                    <br>
                                    დამკვეთის მიერ შერჩეული ავტოსატრანსპორტო საშუალების ტექნიკური მახასიათებლებისა და
                                    დადგენილი ფასის შესაბამისად, შემსრულებელი, იღებს ვალდებულებას, რომ დამკვეთის
                                    მაგივრად განახორციელოს გადახდა კონკრეტული აუქციონის ორგანიზატორი უცხოური კომპანიის
                                    საბანკო ანგარიშზე ( რადგან , ავტო აუქციონი იღებს და ასახავს მხოლოდ მათ პლათფორმაზე
                                    რეგისტრირებულ ლიცენზიის მქონე კმპანიისგან ჩარიცხულ თანხებს). აღნიშნული თანხა
                                    წარმოადგენს დამკვეთის მიერ გადასახდელ ავტომანქანის სააუქციონო ღირებულებას.
                                    <br> <br>
                                    შემსრულებელი განახორციელებს დამკვეთის მიერ შერჩეული ავტოსატრანსპორტო საშუალების
                                    ტრანსპორტირებას ამერიკის ტერიტორიაზე.
                                    <br>
                                    შემსრულებელი განახორციელებს დამკვეთის მიერ შერჩეული ავტოსატრანსპორტო საშუალების
                                    ტრანსპორტირებას ამერიკიდან საქართველომდე.
                                </p>
                            </div>
                            {{--AGREEMENT ENG--}}
                            <div class="display-flex space-between cs-mb5" style="position: relative">
                                <p style="font-size: 10px; text-align: justify;margin-bottom: 0;z-index: 10"> After the
                                    Customer transfers the amount
                                    to the account of Carbidpro LLC according to
                                    this invoice, it will be considered that he agrees to all the conditions specified
                                    in the invoice. Among them, the invoice is an agreement between the parties on what
                                    kind of service the Contractor will provide to the Customer.
                                    <br> <br>
                                    <b>Subject of the service:</b>
                                    <br>
                                    In accordance with the technical characteristics of the vehicle selected by the
                                    Customer and the established price, the Contractor undertakes to make a payment on
                                    behalf of the Customer to the bank account of the foreign company organizing the
                                    specific auction (since the auto auction accepts and reflects only the funds
                                    transferred from a licensed company registered on their platform). The specified
                                    amount is the auction price of the vehicle to be paid by the Customer.
                                    <br> <br>
                                    The Contractor will transport the vehicle selected by the Customer within the
                                    territory of the United States.
                                    <br>
                                    The contractor will transport the vehicle selected by the customer from America to
                                    Georgia.
                                </p>
                                <img id="image" style="height: 90px;position: absolute;bottom:-20px;right: 0"
                                     src="{{asset('invoiceAssets/assets/img/signature.jpg')}}" alt="...">
                            </div>
                            {{--Signature--}}
                            {{--                            <div class="display-flex justify-content-center cs-mb15">--}}
                            {{--                                <div class="cs-mt5">--}}
                            {{--                                    <div class="display-flex">--}}
                            {{--                                        <div>--}}
                            {{--                                            <img style="height: 100px"--}}
                            {{--                                                 src="{{asset('invoiceAssets/assets/img/signature.jpg')}}" alt="...">--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--Download Print--}}
                            <div class="display-flex justify-content-center cs-mb15">
                                <div id="removable" class="display-flex justify-content">
                                    <button id="download_btn" class="cs-invoice_btn btn-blanck cs-primary_color">
{{--                                        <svg class="cs-primary_color" xmlns="http://www.w3.org/2000/svg" class="ionicon"--}}
{{--                                             viewBox="0 0 512 512">--}}
{{--                                            <title>Download</title>--}}
{{--                                            <path--}}
{{--                                                    d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40"--}}
{{--                                                    fill="none" stroke="currentColor" stroke-linecap="round"--}}
{{--                                                    stroke-linejoin="round"--}}
{{--                                                    stroke-width="32"/>--}}
{{--                                            <path fill="none" stroke="currentColor" stroke-linecap="round"--}}
{{--                                                  stroke-linejoin="round"--}}
{{--                                                  stroke-width="32" d="M176 272l80 80 80-80M256 48v288"/>--}}
{{--                                        </svg>--}}
{{--                                        <span>Download</span>--}}
                                    </button>
                                    <div class="cs-m0 tm-align-item-center cs-invoice_btns tm-align-item-center cs-hide_print cs-p0">
                                        <a href="javascript:window.print()" class="cs-invoice_btn cs-p0">
                                            <svg class="cs-primary_color" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 512 512">
                                                <path
                                                        d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                                                        fill="none" stroke="currentColor" stroke-linejoin="round"
                                                        stroke-width="32"/>
                                                <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32"
                                                      fill="none"
                                                      stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                                <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24"
                                                      fill="none"
                                                      stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                                <circle cx="392" cy="184" r="24"/>
                                            </svg>
                                            <span id="print" class="cs-primary_color">Print</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>

    const amounts = document.querySelectorAll('.amounts');
    const payment = document.getElementById('payment')
    const amountDue = document.getElementById('amountDue')



    function totalCalc() {
        let total = 0;
        amounts.forEach((element) => {
            total += parseFloat(element.value);
        });
        document.getElementById('total').innerHTML = '$' + total
        amountDue.innerHTML = '$' + (total - parseInt(payment.value.replace('$', '')))
    }


    totalCalc()
    amounts.forEach((e) => {
        e.addEventListener('input', () => {
            totalCalc()
        })
    })

    payment.addEventListener('input', () => {
        let currentValue = payment.value.replace(/[^0-9.]/g, '');
        payment.value = '$' + currentValue;
        totalCalc()
    })

</script>


<script>
    document.getElementById('print').addEventListener('click', function () {
        document.getElementById('download_btn').remove()
        window.print()
    })
</script>
<script src="{{asset('invoiceAssets/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('invoiceAssets/assets/js/jspdf.min.js')}}"></script>
<script src="{{asset('invoiceAssets/assets/js/html2canvas.min.js')}}"></script>
<script src="{{asset('invoiceAssets/assets/js/main.js')}}"></script>
</body>

</html>