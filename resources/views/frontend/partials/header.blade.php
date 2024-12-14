<div class="up">
    <a href="#" class="scrollup text-center"><i class="fas fa-chevron-up"></i></a>
</div>
<!-- Start of header section
                                             ============================================= -->
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
                                    <a href="/">{{ $tr->translate('Home') }}
                                    </a>
                                </li>
                                <li><a href="/about">{{ $tr->translate('About us') }}</a></li>
                                <li><a href="/announcements">{{ $tr->translate('Announcements') }}</a></li>
                                <li><a href="/contact">{{ $tr->translate('Contact') }}</a></li>
                                <li><a href="/calculator">{{ $tr->translate('Calculator') }}</a></li>
                                <li class="{{ session()->has('auth') ? 'dropdown' : '' }}"
                                    style="background: #e2304e;padding: 9px;border-radius: 6px;">
                                    @if (session()->has('auth'))
                                        <a href="#" class="nav-link dropdown-toggle"
                                            data-bs-toggle="dropdown">{{ session()->get('auth')->contact_name }}</a>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="{{ route('customer.dashboard') }}" class="dropdown-item">
                                                {!! $tr->translate('My cars') !!}
                                            </a>
                                            @if (auth()->user() && !auth()->user()->hasRole('portmanager'))
                                                @if (session()->get('auth')->parent_of <= 0)
                                                    <a href="{{ route('customer.teamList') }}" class="dropdown-item">
                                                        {!! $tr->translate('Add Team') !!}
                                                    </a>
                                                @endif
                                            @endif

                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('customer.logout') }}"
                                                class="dropdown-item">{{ $tr->translate('Logout') }}</a>
                                        </div>
                                    @else
                                        <a href="{{ route('customer.login.get') }}">{{ $tr->translate('LOGIN') }}</a>
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
                                <a href="!#"><img src="assets/img/logo/logo4.png" alt=""></a>
                            </div>
                            <nav class="mobile-main-navigation  clearfix ul-li">
                                <ul id="m-main-nav" class="navbar-nav text-capitalize clearfix">
                                    <li>
                                        <a href="/">{{ $tr->translate('Home') }}
                                        </a>
                                    </li>
                                    <li><a href="/about">{{ $tr->translate('About us') }}</a></li>
                                    <li><a href="/announcements">{{ $tr->translate('Announcements') }}</a></li>
                                    <li><a href="/contact">{{ $tr->translate('Contact') }}</a></li>
                                    <li><a href="/calculator">{{ $tr->translate('Calculator') }}</a></li>
                                    <li class="{{ session()->has('auth') ? 'dropdown' : '' }}"
                                        style="">
                                        @if (session()->has('auth'))
                                            <a href="#" class="nav-link dropdown-toggle"
                                                data-bs-toggle="dropdown">{{ session()->get('auth')->contact_name }}</a>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="{{ route('customer.dashboard') }}" class="dropdown-item">My
                                                    cars</a>
                                                @if (auth()->user() && !auth()->user()->hasRole('portmanager'))
                                                    @if (session()->get('auth')->parent_of <= 0)
                                                        <a href="{{ route('customer.teamList') }}"
                                                            class="dropdown-item">Add
                                                            Team</a>
                                                    @endif
                                                @endif

                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('customer.logout') }}"
                                                    class="dropdown-item">{{ $tr->translate('Logout') }}</a>
                                            </div>
                                        @else
                                            <a
                                                href="{{ route('customer.login.get') }}">{{ $tr->translate('LOGIN') }}</a>
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