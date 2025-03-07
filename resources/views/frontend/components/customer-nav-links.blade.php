<div class="row justify-content-center" style="margin-top:90px">
    <div class="col-lg-12">
        <div class="d-flex mt-4 justify-content-between align-middle flex-column flex-md-row">

            <ul style="padding-left: 0">
                @if(auth()->user()->newwebsitecustomer===0)
                    <li class="tabs__item ">
                        <form class="m-auto m-md-0" action="{{route('generate.link')}}" target="_blank">
                            <input type="hidden" name="customer_id" value="{{auth()->user()->id}}">
                            <button style="border: none;border-radius: 5px;padding:3px 10px;background: #2f5496;color: white;cursor: pointer"
                                    class="m-auto m-md-0">
                                Old Version
                            </button>
                        </form>
                    </li>
                @endif
                <li class="tabs__item ">
                    <a href="{{ route('customer.dashboard') }}"
                       class="m-auto m-md-0 {{ request()->routeIs('customer.dashboard')  ? 'active' : '' }}">
                        {{Cache::get('dashboardStatics' . session()->get('locale'))['My Cars']}}
                    </a>
                </li>
                @if (!auth()->user()->hasRole('portmanager'))
                    <li class="tabs__item ">
                        <a href="{{ route('customer.archivedcars') }}"
                           class="m-auto m-md-0 {{ request()->routeIs('customer.archivedcars') ? 'active' : '' }}">
                            {{Cache::get('dashboardStatics' . session()->get('locale'))['Car History']}}
                        </a>
                    </li>
                @endif
                @if (!auth()->user()->hasRole('portmanager'))
                    <li class="tabs__item tabs__item_active ">
                        <a href="{{ route('customer.payment_history') }}"
                           class="m-auto m-md-0 {{ request()->routeIs('customer.payment_history') ? 'active' : '' }}">
                            {{Cache::get('dashboardStatics' . session()->get('locale'))['Payment History']}}</a>
                    </li>
                @endif
            </ul>
            @if(!request()->routeIs('customer.payment_history'))
                <div style="width: max-content;background: #F2F2F2;" class="p-3 align-self-center">
                    @if($pending)
                        <p style="color:blue; word-wrap: break-word;word-break: break-word;font-size: 14px"
                           class="text-center mt-3">
                            {{Cache::get('dashboardStatics' . session()->get('locale'))['payment_confirmation']}}
                        </p>
                    @endif
                    <form action="{{route('customer.payment_registration_submit')}}" class="text-center"
                          method="post" id="amountform" onsubmit="changeButtonType()">
                        @csrf
                        <div style="text-align: right ">
                            <div class="d-flex flex-column flex-md-row justify-content-between text-center gap-2">
                                <div>
                                    <label style="font-size: 14px"
                                           for="bank_payment"> {{Cache::get('dashboardStatics' . session()->get('locale'))['Transferred Amount']}} </label>
                                    <br class="hideBr">
                                    <input style="width: 200px;" class="mb-2" id="bank_payment" type="text"
                                           placeholder="$"
                                           name="bank_payment">
                                </div>
                                <div>
                                    <span style="font-size: 14px">Pending:</span>
                                    <span style="color: blue;font-size: 14px"> ${{ $pending }} </span>
                                </div>
                            </div>

                            <span id="something" style="color: red; display: none;width: 100%!important;text-align: center!important" ></span>
                            <div class="d-flex flex-column flex-md-row justify-content-between text-center gap-2 mt-2">

                                <div>
                                    <label style="font-size: 14px" for="full_name">
                                        {{Cache::get('dashboardStatics' . session()->get('locale'))['Sender']}}
                                        ({{Cache::get('dashboardStatics' . session()->get('locale'))['Full Name']}})
                                    </label>
                                    <br class="hideBr">

                                    <br class="hideBr">
                                    <input style="width: 200px;margin-left: 7px" id="full_name" type="text"
                                           name="full_name" placeholder="Full Name"
                                           oninput="
                                             regex = /^[A-Za-z\s]*$/;
                                             errorElement = document.getElementById('something');
                                            // If the input doesn't match the allowed characters:
                                            if (!regex.test(this.value)) {
                                              errorElement.innerText = 'Only Latin characters';
                                              errorElement.style.display = 'block';
                                              this.value = this.value.replace(/[^A-Za-z\s]+/g, '');
                                            } else {
                                              errorElement.innerText = '';
                                              errorElement.style.display = 'none';
                                            }"
                                    >
                                </div>

                                @if(session()->get('locale')==='en')
                                    <button style="border: none;border-radius: 5px;padding:3px 10px;background: #2f5496;color: white;"
                                            id="submitBtn">
                                        {{Cache::get('dashboardStatics' . session()->get('locale'))['Submit']}}
                                    </button>
                                @endif
                            </div>
                            @if(session()->get('locale')==='ru')
                                <button style="border: none;border-radius: 5px;padding:3px 10px;background: #2f5496;color: white;margin-right: 10px"
                                        id="submitBtn">
                                    {{Cache::get('dashboardStatics' . session()->get('locale'))['Submit']}}
                                </button>
                            @endif
                            <p style="font-size: 20px"
                               class="text-center mt-3">{{Cache::get('dashboardStatics' . session()->get('locale'))['My Deposit']}}
                                :<span
                                        style="color: green"> ${{ $balance }}</span>
                            </p>
                        </div>
                    </form>

                    {{--  ===============  Transfer to old account  ================  --}}
                    @if(auth()->user()->newwebsitecustomer===0)
                        <div style="text-align: right ">
                            <form action="{{route('transfer.to.old')}}">
                                <div class="d-flex flex-column flex-md-row justify-content-between text-center gap-2 mt-2">
                                    <div>
                                        <label style="font-size: 14px"
                                               for="full_name">
                                            Transfer Deposit to Old
                                        </label>
                                        <br class="hideBr">
                                        <input style="width: 200px;margin-left: 7px" id="full_name" type="text"
                                               name="amount">
                                    </div>
                                    <button style="border: none;border-radius: 5px;padding:3px 10px;background: #2f5496;color: white;">
                                        Transfer
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>


<div class="d-flex justify-content-center">
    @session('error')
    <p class="alert alert-danger mb-0">{{ session('error') }}</p>
    @endsession
    @session('success')
    <p class="alert alert-success mb-0">{{ session('success') }}</p>
    @endsession
    @if($errors->any())
        <ul style="all: unset">
            @foreach($errors->all() as $error)
                <li class="alert alert-danger mb-2">{{$error}}</li>
            @endforeach
        </ul>
    @endif
</div>

