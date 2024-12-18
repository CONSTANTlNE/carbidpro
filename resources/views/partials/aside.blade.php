<aside class="main-sidebar">
    <!-- sidebar -->
    <div class="sidebar">
        <!-- sidebar menu -->
        <ul class="sidebar-menu">

            <li class="{{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer"></i><span>Dashboard</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>

            @if (auth()->user()->hasRole('Admin'))
                <li class="{{ Route::is('paymentreports.index') ? 'active' : '' }}">
                    <a href="{{ route('paymentreports.index') }}">
                        <i class="fa fa-money"></i><span>Payment Reports</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endif


            @if (auth()->user()->hasRole('Admin'))
                <li class="{{ Route::is('users.index') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="fa fa-user-circle"></i><span>User</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
                <li class="{{ Route::is('customers.index') ? 'active' : '' }}">
                    <a href="{{ route('customers.index') }}">
                        <i class="fa fa-user-circle"></i><span>Customers</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>

            @endif


            @if (auth()->user()->hasRole('Dispatch'))
                <li class="{{ Route::is('car.showStatus') ? 'active' : '' }}">
                    <a href="/dashboard/cars/status/for-dispatch">
                        <i class="fa fa-automobile"></i><span>Cars</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @elseif(auth()->user()->hasRole('Admin'))
                <li class="{{ Route::is('cars.index') ? 'active' : '' }}">
                    <a href="{{ route('cars.index') }}">
                        <i class="fa fa-automobile"></i><span>Cars</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endif


            @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Loader'))
                <li class="">
                    <a href="/dashboard/containers/status/for-load">
                        <i class="fa fa-ship"></i><span>Containers</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @elseif(auth()->user()->hasRole('Finance'))
                <li class="">
                    <a href="{{ route('container.showStatus', 'loaded-payments') }}">
                        <i class="fa fa-ship"></i><span>Containers</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Terminal Agent'))
                <li class="">
                    <a href="{{ route('arrived.index') }}">
                        <i class="fa fa-anchor"></i><span>Arrived</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('Admin'))
                <li class="">
                    <a href="{{ route('portemail.index') }}">
                        <i class="fa fa-envelope"></i><span>Port Emails</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endif
            <hr>
            @if (auth()->user()->hasRole('Admin'))
                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-gear"></i><span>Data</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left float-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="{{ route('auctions.index') }}">
                                <span>Auctions</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('locations.index') }}">
                                <span>Locations</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('shipping-prices.index') }}">
                                <span>Shipping Prices</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('load-types.index') }}">
                                <span>Load Types</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('ports.index') }}">
                                <span>Ports</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif
        </ul>
    </div>
    <!-- /.sidebar -->
</aside>
