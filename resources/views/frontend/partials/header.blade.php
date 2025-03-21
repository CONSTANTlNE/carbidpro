@php
    use Illuminate\Support\Facades\Cache;use Illuminate\Support\Facades\Session;

//         TRANSLATE STATICK WORDS AND PUT IN CACHE
           $headerStatics=Cache::get('headerStatics'.session()->get('locale'));

            if($headerStatics===null){

                $data=[
                    'Home'=> $tr->translate('Home'),
                    'About us'=>$tr->translate('About us'),
                    'Announcements'=>$tr->translate('Announcements'),
                    'Contact'=>$tr->translate('Contact'),
                    'Calculator'=>$tr->translate('Calculator'),
                    'Login'=>$tr->translate('Login'),
                    'Logout'=>$tr->translate('Logout'),
                    'Register'=>$tr->translate('Register'),
                    'Add Team'=>$tr->translate('Add Team'),
                    'My cars'=>$tr->translate('My cars'),
                ];

                Cache::forever('headerStatics'.session()->get('locale'), $data);
                $headerStatics=Cache::get('headerStatics'.session()->get('locale'));

            }

@endphp

<div class="up">
    <a href="#" class="scrollup text-center"><i class="fas fa-chevron-up"></i></a>
</div>


<header id="ft-header" class="ft-header-section header-style-one">
    <div class="ft-header-content-wrapper position-relative">
        <div class="container">
            <div class="ft-header-content position-relative">
                <div class="ft-brand-logo">
                    <a href="/"><img src="{{ asset('frontendAssets/images/CARBID.gif') }}" alt="logo"></a>
                </div>
                <div class="ft-header-menu-top-cta position-relative">

                    <div class="ft-header-main-menu d-flex justify-content-end align-items-center">
                        <nav class="ft-main-navigation clearfix ul-li">
                            <ul id="ft-main-nav" class="nav navbar-nav clearfix">
                                <li>


                                    <a href="/">{{ $headerStatics['Home'] }}
                                    </a>
                                </li>
                                <li>
                                    <a href="/about">{{ $headerStatics['About us'] }}</a>
                                </li>
                                <li>
                                    <a href="/announcements">{{$headerStatics['Announcements'] }}</a>
                                </li>
                                <li>
                                    <a href="/contact">{{ $headerStatics['Contact'] }}</a>
                                </li>
                                @auth()
{{--                                    <li>--}}
{{--                                        <a href="{{route('insurance.show')}}">Insurance</a>--}}
{{--                                    </li>--}}
                                    <li>
                                        <a href="{{route('calculator.index')}}">{{ $headerStatics['Calculator'] }}</a>
                                    </li>
                                @endauth
                                <li class="{{ session()->has('auth') ? 'dropdown' : '' }}"
                                    style="background: #e2304e;padding: 9px;border-radius: 6px;">
                                    @if (session()->has('auth'))
                                        <a style="padding: 0!important;padding: 0!important" href="#" class="nav-link dropdown-toggle"
                                           data-bs-toggle="dropdown">{{ session()->get('auth')->contact_name }}</a>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="{{ route('customer.dashboard') }}" class="dropdown-item">
                                                {{ $headerStatics['My cars'] }}
                                            </a>
                                            @if (auth('customer')->user() && !auth('customer')->user()->hasRole('portmanager'))
                                                @if (session()->get('auth')->child_of <= 0)
                                                    <a href="{{ route('customer.teamList') }}" class="dropdown-item">
                                                        {{ $headerStatics['Add Team'] }}
                                                    </a>
                                                @endif
                                            @endif
                                            <a href="{{ route('customer.terms') }}" class="dropdown-item">My Info</a>

                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('customer.logout') }}"
                                               class="dropdown-item">{{ $headerStatics['Logout'] }}</a>
                                        </div>
                                    @else
                                        <a href="{{ route('customer.login.get') }}">{{ $headerStatics['Login'] }}</a>
                                    @endif
                                </li>
                                @if (!auth('customer')->check())
                                <li>
                                    <a href="{{ route('customer.register.get') }}">{{ $headerStatics['Register'] }}</a>
                                </li>
                                @endif
                                @if (session()->get('locale') == 'en')
                                    <li><a href="{{ route('set-locale', 'ru') }}">RU</a>
                                    </li>
                                @else
                                    <li><a href="{{ route('set-locale', 'en') }}">EN</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        <div class="ft-header-cta-btn">

                        </div>
                    </div>
                </div>
                <div class="mobile_menu position-relative">
                    <div class="mobile_menu_button open_mobile_menu">
                        <i class="fal fa-bars"></i>
                    </div>
                    <div class="mobile_menu_wrap">
                        <div class="mobile_menu_overlay open_mobile_menu"></div>
                        <div class="mobile_menu_content">
                            <div class="mobile_menu_close open_mobile_menu">
                                <i class="fal fa-times"></i>
                            </div>
                            <div class="m-brand-logo">
                                <a href="!#"><img src="frontendAssets/img/logo/logo4.png" alt=""></a>
                            </div>
                            <nav class="mobile-main-navigation  clearfix ul-li">
                                <ul id="m-main-nav" class="navbar-nav text-capitalize clearfix">
                                    <li>
                                        <a href="/">{{ $headerStatics['Home'] }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/about">{{ $headerStatics['About us'] }}</a>
                                    </li>
                                    <li>
                                        <a href="/announcements">{{$headerStatics['Announcements'] }}</a>
                                    </li>
                                    <li>
                                        <a href="/contact">{{ $headerStatics['Contact'] }}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('calculator.index')}}">
                                            {{ $headerStatics['Calculator'] }}
                                            </a>
                                    </li>
                                    <li class="{{ session()->has('auth') ? 'dropdown' : '' }}"
                                        style="">
                                        @if (session()->has('auth'))
                                            <a href="#" class="nav-link dropdown-toggle"
                                               data-bs-toggle="dropdown">{{ session()->get('auth')->contact_name }}</a>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="{{ route('customer.dashboard') }}" class="dropdown-item">My
                                                    cars</a>
                                                @if (auth('customer')->user() && !auth('customer')->user()->hasRole('portmanager'))
                                                    @if (session()->get('auth')->child_of <= 0)
                                                        <a href="{{ route('customer.teamList') }}"
                                                           class="dropdown-item">
                                                            Add Team
                                                        </a>
                                                    @endif
                                                @endif
                                                <a href="{{ route('customer.terms') }}" class="dropdown-item">
                                                    My Info
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('customer.logout') }}"
                                                   class="dropdown-item">{{ $headerStatics['Logout'] }}</a>
                                            </div>
                                        @else
                                            <a  href="{{ route('customer.login.get') }}">{{$headerStatics['Login'] }}</a>
                                        @endif
                                    </li>

                                    @if (session()->get('locale') == 'en')
                                        <li><a href="{{ route('set-locale', 'ru') }}">RU</a>
                                        </li>
                                    @else
                                        <li><a href="{{ route('set-locale', 'en') }}">EN</a>
                                        </li>
                                    @endif

                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- /Mobile-Menu -->
                </div>

            </div>
        </div>

    </div>
</header>
<!-- End of header section -->
<dialog>

</dialog>