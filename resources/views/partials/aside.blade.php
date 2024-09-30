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
                <li class="{{ Route::is('users.index') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="fa fa-user-circle"></i><span>User</span>
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
            @else
                <li class="{{ Route::is('cars.index') ? 'active' : '' }}">
                    <a href="{{ route('cars.index') }}">
                        <i class="fa fa-automobile"></i><span>Cars</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
    <!-- /.sidebar -->
</aside>
