<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex mt-4 justify-content-between">
            <ul style="padding-left: 0">
                <li class="tabs__item ">
                    <a href="{{ route('customer.dashboard') }}"
                       class="{{ request()->routeIs('customer.dashboard')  ? 'active' : '' }}">
                        {{Cache::get('dashboardStatics'.session()->get('locale'))['My Cars']}}</a>
                </li>
                @if (!auth()->user()->hasRole('portmanager'))
                    <li class="tabs__item ">
                        <a href="{{ route('customer.archivedcars') }}"
                           class="{{ request()->routeIs('customer.archivedcars') ? 'active' : '' }}">
                            {{Cache::get('dashboardStatics'.session()->get('locale'))['Car History']}}
                        </a>
                    </li>
                @endif
                @if (!auth()->user()->hasRole('portmanager'))
                    <li class="tabs__item tabs__item_active ">
                        <a href="{{ route('customer.payment_history') }}"
                           class="{{ request()->routeIs('customer.payment_history') ? 'active' : '' }}">
                            {{Cache::get('dashboardStatics'.session()->get('locale'))['Payment History']}}</a>
                    </li>
                @endif
            </ul>
            <div style="width: min-content">
                <form action="{{route('customer.payment_registration_submit')}}" class="text-center"
                      method="post">
                    @csrf
                    <div style="text-align: right ">
                        <label for="bank_payment"> {{Cache::get('dashboardStatics'.session()->get('locale'))['Transferred Amount']}} </label>
                        <input class="mb-2" id="bank_payment" type="number" name="bank_payment">
                        <br>
                        <label for="full_name">{{Cache::get('dashboardStatics'.session()->get('locale'))['Sender']}}({{Cache::get('dashboardStatics'.session()->get('locale'))['Full Name']}})</label>
                        <input  id="full_name" type="text" name="full_name">
                        <br>
                        <br>
                        <button style="border: none;border-radius: 5px;padding: 10px;background: #2f5496;color: white"
                                class="mt-2">
                            {{Cache::get('dashboardStatics'.session()->get('locale'))['Submit']}}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@if($pending)
    <p class="text-center mt-3">Pending: <span style="color: blue"> ${{ $pending }} </span>
        <span style="color: blue">
                            {{Cache::get('dashboardStatics'.session()->get('locale'))['payment_confirmation']}}
                        </span>
    </p>
@endif
<p class="text-center">{{Cache::get('dashboardStatics'.session()->get('locale'))['Deposit']}} :<span style="color: green"> ${{ $balance }}</span></p>

<div class="d-flex justify-content-center">
    @session('error')
    <p class="alert alert-danger mb-0">{{ session('error') }}</p>
    @endsession
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li class="alert alert-danger mb-2">{{$error}}</li>
            @endforeach
        </ul>
    @endif
</div>