
<header class="main-header">
    <a href="{{route('dashboard')}}" class="logo">
        <!-- Logo -->
        <span class="logo-mini">
            <h2 style="color: white"> CARBIDPRO</h2>
        </span>
        <span class="logo-lg">
            <h2 style="color: white"> CARBIDPRO</h2>
        </span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-expand py-0">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <!-- Sidebar toggle button-->
            <span class="sr-only">Toggle navigation</span>
            <span class="pe-7s-angle-left-circle"></span>
        </a>

           <p style="color: white">{{auth()->user()->name}} </p>
           <p style="color: white;margin-left: 30px" id="countdown"></p>

        <div class="collapse navbar-collapse navbar-custom-menu">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown notifications-menu">
                    <a class="nav-link admin-notification" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="pe-7s-bell"></i>
                        @if($deposits>0 || $customerscount>0)
                        <span class="label bg-danger">{{$deposits+$customerscount}}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu drop_drops custom_drop_scroll" tabindex="3" style="overflow: hidden; outline: none;">
                        @if($balancecomposers)
                            @foreach($balancecomposers as $balancecomposer)
                                <a class="dropdown-item" href="#">
                                    <div class="menues">
                                        <p>
                                            <i class="fa fa-dot-circle-o color-red"></i>
                                           Deposit : {{$balancecomposer->customer->contact_name}} - {{$balancecomposer->amount}}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                        @if($newcustomers)
                            @foreach($newcustomers as $newcustomer)
                                <a class="dropdown-item" href="#">
                                    <div class="menues">
                                        <p>
                                            <i class="fa fa-dot-circle-o color-red"></i>
                                           New Customer : {{$newcustomer->contact_name}}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        @endif


                    </div>
                </li>
                <!-- User -->

                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <img src="/assets/dist/img/avatar5.png" class="rounded-circle" width="50" height="50"
                            alt="user"></a>

                    <div class="dropdown-menu drop_down">
                        <div class="menus">
                            <a class="dropdown-item" href="#"><i class="fa fa-user"></i> User Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="fa fa-sign-out"></i>
                                Signout</a>

                        </div>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

                </li>
            </ul>
        </div>
    </nav>
</header>
