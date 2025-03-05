@extends('layouts.app')
@section('content')

    {{--@dd(auth()->user());--}}
    <!--preloader-->

{{--    <div id="preloader">--}}
{{--        <div id="status"></div>--}}
{{--    </div>--}}

    <!-- Site wrapper -->
    <div class="wrapper">
        @include('partials.header')
        <!-- =============================================== -->
        <!-- Left side column. contains the sidebar -->
        @include('partials.aside')
        <!-- =============================================== -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="header-icon">
                    <i class="fa fa-dashboard"></i>
                </div>
                <div class="header-title">
                    <h1>CARBIDPRO</h1>
                </div>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row px-5">
                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox1">
                            <div style="background: black!important;" class="statistic-box">
                                <i class="fa fa-user-plus fa-3x"></i>
                                <div class="counter-number pull-right">
                                    <span class="count-number"></span>
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3>Total Customers <span style="color: darkorange">{{$totalCustomers}}</span></h3>
                            </div>
                        </div>
                    </div>

                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox1">
                            <div style="background: black!important;" class="statistic-box">
                                <i class="fa fa-user-plus fa-3x"></i>
                                <div class="counter-number pull-right">
                                    <span class="count-number"></span>
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3>Total Shipping <span style="color: darkorange">{{$totalShipping}}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Payments--}}
                <div class="row px-5">
                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox4">
                            <div style="background: black!important;" class="statistic-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#009688" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path stroke-linecap="round"
                                              d="M12 6v12m3-8.5C15 8.12 13.657 7 12 7S9 8.12 9 9.5s1.343 2.5 3 2.5s3 1.12 3 2.5s-1.343 2.5-3 2.5s-3-1.12-3-2.5"/>
                                    </g>
                                </svg>
                                <div class="counter-number pull-right">
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3> Total Deposits Received <span style="color: darkorange">{{$totalDeposits}}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox4">
                            <div style="background: black!important;" class="statistic-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#FF8C00" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path stroke-linecap="round"
                                              d="M12 6v12m3-8.5C15 8.12 13.657 7 12 7S9 8.12 9 9.5s1.343 2.5 3 2.5s3 1.12 3 2.5s-1.343 2.5-3 2.5s-3-1.12-3-2.5"/>
                                    </g>
                                </svg>
                                <div class="counter-number pull-right">
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3> Total Spent on cars <span style="color: #FF8C00">{{$totalSpent}}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox4">
                            <div style="background: black!important;" class="statistic-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#FE0000" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path stroke-linecap="round"
                                              d="M12 6v12m3-8.5C15 8.12 13.657 7 12 7S9 8.12 9 9.5s1.343 2.5 3 2.5s3 1.12 3 2.5s-1.343 2.5-3 2.5s-3-1.12-3-2.5"/>
                                    </g>
                                </svg>
                                <div class="counter-number pull-right">
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3> Total Amount Due <span style="color:#FE0000">{{ $totalAmountDue}}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
                {{--CARS--}}
                <div class="row px-5">
                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox4">
                            <div style="background: black!important;" class="statistic-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                     viewBox="0 0 2048 2048">
                                    <path fill="#009688"
                                          d="M384 1152q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10m1280 0q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10m347-256l-66 65q2 5 10 30t19 59t25 73t24 71t18 54t7 22v650q0 27-10 50t-27 40t-41 28t-50 10h-128q-27 0-50-10t-40-27t-28-41t-10-50H384q0 27-10 50t-27 40t-41 28t-50 10H128q-27 0-50-10t-40-27t-28-41t-10-50v-650l7-21l18-54l24-72q13-39 24-73t20-59t10-30l-66-65H0V768h91l57 58l74-223q16-49 46-89t71-69t87-45t100-16h996q52 0 99 16t88 44t70 69t47 90l74 223l57-58h91v128zM526 512q-63 0-112 36t-70 95l-85 253h1530l-85-253q-20-59-69-95t-113-36zm882 1231l-104-207H744l-104 207v49h768zm512 49v-502l-6-18q-6-18-15-47t-21-61t-21-63t-17-51t-9-26H217q-2 5-9 26t-17 50t-21 63t-20 62t-16 46t-6 19v502h384v-79l152-305h720l152 305v79z"/>
                                </svg>
                                <div class="counter-number pull-right">
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3> Total Cars <span style="color: #009688">{{$totalCars}}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox3">
                            <div style="background: black!important;" class="statistic-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                     viewBox="0 0 2048 2048">
                                    <path fill="#FF8C00"
                                          d="M384 1152q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10m1280 0q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10m347-256l-66 65q2 5 10 30t19 59t25 73t24 71t18 54t7 22v650q0 27-10 50t-27 40t-41 28t-50 10h-128q-27 0-50-10t-40-27t-28-41t-10-50H384q0 27-10 50t-27 40t-41 28t-50 10H128q-27 0-50-10t-40-27t-28-41t-10-50v-650l7-21l18-54l24-72q13-39 24-73t20-59t10-30l-66-65H0V768h91l57 58l74-223q16-49 46-89t71-69t87-45t100-16h996q52 0 99 16t88 44t70 69t47 90l74 223l57-58h91v128zM526 512q-63 0-112 36t-70 95l-85 253h1530l-85-253q-20-59-69-95t-113-36zm882 1231l-104-207H744l-104 207v49h768zm512 49v-502l-6-18q-6-18-15-47t-21-61t-21-63t-17-51t-9-26H217q-2 5-9 26t-17 50t-21 63t-20 62t-16 46t-6 19v502h384v-79l152-305h720l152 305v79z"/>
                                </svg>
                                <div class="counter-number pull-right">
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3>Total Cars (Paid) <span style="color: #FF8C00">{{$totalCarsPaid}}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class=" col-sm-6 col-md-6 col-lg-4">
                        <div style="background: black!important;" id="cardbox2">
                            <div style="background: black!important;" class="statistic-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                     viewBox="0 0 2048 2048">
                                    <path fill="#FE0000"
                                          d="M384 1152q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10m1280 0q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10m347-256l-66 65q2 5 10 30t19 59t25 73t24 71t18 54t7 22v650q0 27-10 50t-27 40t-41 28t-50 10h-128q-27 0-50-10t-40-27t-28-41t-10-50H384q0 27-10 50t-27 40t-41 28t-50 10H128q-27 0-50-10t-40-27t-28-41t-10-50v-650l7-21l18-54l24-72q13-39 24-73t20-59t10-30l-66-65H0V768h91l57 58l74-223q16-49 46-89t71-69t87-45t100-16h996q52 0 99 16t88 44t70 69t47 90l74 223l57-58h91v128zM526 512q-63 0-112 36t-70 95l-85 253h1530l-85-253q-20-59-69-95t-113-36zm882 1231l-104-207H744l-104 207v49h768zm512 49v-502l-6-18q-6-18-15-47t-21-61t-21-63t-17-51t-9-26H217q-2 5-9 26t-17 50t-21 63t-20 62t-16 46t-6 19v502h384v-79l152-305h720l152 305v79z"/>
                                </svg>
                                <div class="counter-number pull-right">
                                    <span class="slight"><i class="fa fa-play fa-rotate-270"> </i>
                                </span>
                                </div>
                                <h3> Total Cars (Due) <span style="color:#FE0000">{{$totalCarsDue}}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- /.content -->
        </div>

        @include('partials.footer')
    </div>
@endsection
