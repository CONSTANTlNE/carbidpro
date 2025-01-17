@extends('layouts.app')

@section('selectedsms')
    @include('partials.header')
    @include('partials.aside')

    <div class="content-wrapper">
        <section class="content-header" style="height: 60px">
            <div class="header-icon">
                <i class="fa fa-mobile"></i>
            </div>
            <div class="header-title">
                <h1>Send Sms To Selected Customers</h1>
            </div>
        </section>
        @if($errors->any())
            <div style="padding: 5px!important;" class="ml-3 alert custom_alerts alert-danger alert-dismissible fade show w-25" role="alert">
                @foreach($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-lg-8" >
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('sms.send.all')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea name="message" id="" class="w-100" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-add"><i class="fa fa-check"></i> send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- Modal js -->
    <script src="{{asset('assets/plugins/modals/classie.js')}}"></script>
    <script src="{{asset('assets/plugins/modals/modalEffects.js')}}"></script>
    <!-- End Page Lavel Plugins

@endsection