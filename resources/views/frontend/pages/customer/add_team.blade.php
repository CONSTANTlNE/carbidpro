@extends('frontend.layout.app')

@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/assets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{!! $tr->translate('Add Team') !!}</h2>

            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <br>

                <div class="auth_page_wrapper">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{!! $tr->translate($error) !!} </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('customer.register.post') }}" method="post">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="">{!! $tr->translate('Role') !!}</label> <br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="dealer" id="yes" checked name="role"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="yes">{!! $tr->translate('Dealer') !!}</label>
                            </div>
                            

                        </div>

                        <input type="hidden" name="team" value="1">

                        <div class="form-group mb-3">
                            <label for="contact_name">{!! $tr->translate('Contact Name') !!}</label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name"
                                placeholder="{!! $tr->translate('Enter your contact name') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">{!! $tr->translate('Email Address') !!} </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="{!! $tr->translate('Enter your email address') !!}" required>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="phone">{!! $tr->translate('Phone') !!} </label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                placeholder="{!! $tr->translate('Enter your Phone') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="extra_for_team">{!! $tr->translate('Extra for Calculator') !!} </label>
                            <input type="number" class="form-control" id="extra_for_team" name="extra_for_team"
                                placeholder="{!! $tr->translate('Enter your Extra') !!}" value="0">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{!! $tr->translate('Password') !!}</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{!! $tr->translate('Enter a strong password') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">{!! $tr->translate('Confirm Password') !!}"</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="{!! $tr->translate('Confirm your password') !!}" required>
                        </div>


                        <button type="submit" class="btn btn-primary">{!! $tr->translate('Add') !!}</button>

                    </form>
                </div>

                <br>

            </div>
        </div>
    </div>
@endsection
