<aside class="main-sidebar">
    <!-- sidebar -->
    <div class="sidebar">
        <!-- sidebar menu -->
        <ul class="sidebar-menu">

            @hasanyrole('Admin|Developer')
            <li class="{{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer"></i><span>Dashboard</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            @endhasanyrole

{{--            @hasanyrole('Dispatch')--}}
{{--            <li class="{{ Route::is('cars.index') ? 'active' : '' }}">--}}
{{--                <a href="{{ route('cars.index') }}"><i class="fa fa-tachometer"></i><span>Cars</span>--}}
{{--                    <span class="pull-right-container"></span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            @endhasanyrole--}}
            <li class="treeview active {{request()->routeIs('cars.index') || request()->routeIs('carpayment.index') ? 'active' : ''}}">

                <a href="#">
                    <i class="fa fa-automobile"></i></i><span>Cars</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left float-right"></i>
                        </span>
                </a>

                <ul class="treeview-menu">
                    @hasanyrole('Admin|Developer')
                    <li class="{{ Route::is('car.create') ? 'active' : '' }}">
                        <a href="{{ route('car.create') }}">
                            <span>Add Car</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    @endhasanyrole
                    @hasanyrole('Admin|Developer')
                    <li class="{{ Route::is('cars.index') ? 'active' : '' }}">
                        <a href="{{ route('cars.index') }}">
                            <span>Current Cars</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    @endhasanyrole
                    @hasanyrole('Admin|Developer')
                    <li class="{{ Route::is('cars.index.trashed') ? 'active' : '' }}">
                        <a href="{{ route('cars.index.trashed', ['archive' => 'archive']) }}">
                            <span>Archived Cars</span>
                            <span class="pull-right-container"></span>
                            @if($archivedCount>0)
                                <span style="background: red!important;color: white!important"
                                      class="pull-right badge">{{$archivedCount}}</span>
                            @endif
                        </a>
                    </li>
                    @endhasanyrole
                </ul>
            </li>

            @hasanyrole('Admin|Developer')
            <li class="treeview active {{request()->routeIs('customer.balance.index') || request()->routeIs('carpayment.index') ? 'active' : ''}}">
                <a href="#">
                    <i class="fa fa-money"></i><span>Payments</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left float-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('customer.balance.index') ? 'active' : '' }}">
                        <a href="{{ route('customer.balance.index') }}">
                            <span>Deposit Requests</span>
                            <span class="pull-right-container"></span>
                            @if($deposits>0)
                                <span style="background: red!important;color: white!important"
                                      class="pull-right badge">{{$deposits}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="{{ Route::is('carpayment.index') ? 'active' : '' }}">
                        <a href="{{ route('carpayment.index') }}">
                            <span>Payment for Cars</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{request()->routeIs('users.index') || request()->routeIs('customers.index') || request()->routeIs('customers.archived') ? 'active' : ''}}">
                <a href="#">
                    <i class="fa fa-user-circle"></i><span>Users</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left float-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu ">
                    <li class="{{request()->routeIs('users.index') ? 'active' : ''}}">
                        <a href="{{ route('users.index') }}">
                            <span>User</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    <li class="{{ Route::is('customers.index') ? 'active' : ''}}">
                        <a href="{{ route('customers.index') }}">
                            <span>Customers</span>
                            <span class="pull-right-container"></span>
                            @if($customerscount>0)
                                <span style="background: red!important;color: white!important"
                                      class="pull-right badge">{{$customerscount}}</span>
                            @endif
                        </a>
                    </li>

                    <li class="{{request()->routeIs('customers.archived') ? 'active' : ''}}">
                        <a href="{{ route('customers.archived',['archive' => 'archive'] ) }}">
                            <span>Archived Customers</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole

            @hasanyrole('Admin|Developer|Loader')
            <li class="">
                <a href="/dashboard/containers/status/for-load">
                    <i class="fa fa-ship"></i><span>Containers</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            @endhasanyrole

            @hasrole('Finance')
            <li class="">
                <a href="{{ route('container.showStatus', 'loaded-payments') }}">
                    <i class="fa fa-ship"></i><span>Containers</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            @endrole

            @hasanyrole('Admin|Terminal Agent|Developer')
            <li class="">
                <a href="{{ route('arrived.index') }}">
                    <i class="fa fa-anchor"></i><span>Arrived</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            @endhasanyrole

            @hasanyrole('Admin|Developer')
            <li class="">
                <a href="{{ route('portemail.index') }}">
                    <i class="fa fa-envelope"></i><span>Port Emails</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-mobile"></i><span>SMS</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left float-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">
                    <li class="">
                        <a href="{{ route('sms.all') }}">
                            <span>Send Sms</span>
                            <span class="pull-right-container"></span>
                        </a>
                        <a href="{{ route('sms.drafts') }}">
                            <span>Drafts</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gear"></i><span>Data</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left float-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">

                    @hasanyrole('Admin|Developer')
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

                    <li class="">
                        <a href="{{ route('countries.index') }}">
                            <span>Countries</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>

                    <li class="">
                        <a href="{{ route('portcities.index') }}">
                            <span>PortCties</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>

                    <li class="">
                        <a href="{{ route('roles.index') }}">
                            <span>Roles and Permissions</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    @endhasanyrole
                    @can('StatesAdd')
                        <li class="">
                            <a href="{{ route('states') }}">
                                <span>States</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>


            <hr>
            @hasanyrole('Admin|Developer')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gear"></i><span>Site Settings</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left float-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">
                    <li class="">
                        <a href="{{ route('sliders.index') }}">
                            <span>Slides</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('settings.index') }}">
                            <span>Settings</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('announcements.index') }}">
                            <span>Announcements</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('services.index') }}">
                            <span>Services</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
        </ul>
    </div>
    <!-- /.sidebar -->
</aside>
