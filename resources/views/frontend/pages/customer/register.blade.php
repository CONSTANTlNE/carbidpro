@extends('frontend.layout.app')

@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/assets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{!! $tr->translate('Register') !!}</h2>

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
                    <form action="{{ route('customer.register.post') }}" method="post">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="number_of_cars">{!! $tr->translate('Number of cars you buy from U.S. every month') !!}</label>

                            <select name="number_of_cars" class="form-control" id="number_of_cars">
                                <option selected>{!! $tr->translate('up to 5 cars') !!}</option>
                                <option>{!! $tr->translate('10 cars') !!}</option>
                                <option>{!! $tr->translate('20 cars') !!}</option>
                                <option>{!! $tr->translate('50 cars') !!}</option>
                                <option>{!! $tr->translate('100+ cars') !!}</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="company_name">{!! $tr->translate('Company Name (optional)') !!}</label>
                            <input type="text" value="{{ old('company_name') }}" class="form-control" id="company_name"
                                name="company_name" placeholder="{!! $tr->translate('Enter your company name') !!}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="personal_number">{!! $tr->translate('Company identification code / Personal ID ') !!}</label>
                            <input type="text" value="{{ old('personal_number') }}" class="form-control"
                                id="personal_number" name="personal_number" placeholder="{!! $tr->translate('Enter your Company identification code / Personal ID') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="contact_name">{!! $tr->translate('Full name') !!}</label>
                            <input type="text" class="form-control" value="{{ old('contact_name') }}" id="contact_name"
                                name="contact_name" placeholder="{!! $tr->translate('Enter your Full name') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">{!! $tr->translate('Phone Number') !!}</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone') }}" placeholder="{!! $tr->translate('Enter your phone number') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">{!! $tr->translate('Email Address') !!} </label>
                            <input type="email" value="{{ old('email') }}" class="form-control" id="email"
                                name="email" placeholder="{!! $tr->translate('Enter your email address') !!}" required>
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

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember" required>
                                <label class="form-check-label" for="remember_me">{!! $tr->translate('I Agree to the') !!} <a
                                        href="https://phplaravel-1240578-4751501.cloudwaysapps.com/terms-and-conditions" target="_blank"><span
                                            style="color: blue;text-decoration:underline">Terms and Conditions</span>
                                    </a></label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{!! $tr->translate('Sign up') !!}</button>

                        <a href="{{ route('customer.login.get') }}" style="font-size:12px">{!! $tr->translate('Already have an account? Login') !!}</a>

                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
@endsection
