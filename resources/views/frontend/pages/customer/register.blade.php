@extends('frontend.layout.app')

@php

    $registerStatics=Cache::get('registerStatics'.session()->get('locale'));

     if($registerStatics===null){

         $data=[
             'Register'=> $tr->translate('Register'),
             'car_numbers'=> $tr->translate('Number of cars you buy from U.S. every month'),
             'cars'=> $tr->translate('cars'),
             'Company Name (optional)'=> $tr->translate('Company Name (optional)'),
             'Enter your company name'=> $tr->translate('Enter your company name'),
             'Company identification code / Personal ID'=> $tr->translate('Company identification code / Personal ID'),
             'Enter your Company identification code / Personal ID'=> $tr->translate('Enter your Company identification code / Personal ID'),
             'Full name'=> $tr->translate('Full name'),
             'Enter your Full name'=> $tr->translate('Enter your Full name'),
             'Enter your phone number'=> $tr->translate('Enter your phone number'),
             'Phone Number'=> $tr->translate('Phone Number'),
             'Email Address'=> $tr->translate('Email Address'),
             'Enter your email address'=> $tr->translate('Enter your email address'),
             'Password'=> $tr->translate('Password'),
             'Enter a strong password'=> $tr->translate('Enter a strong password'),
             'Confirm your password'=> $tr->translate('Confirm your password'),
             'Confirm password'=> $tr->translate('Confirm password'),
             'I Agree to the'=> $tr->translate('I Agree to the'),
             'Terms and Conditions'=> $tr->translate('Terms and Conditions'),
             'Sign up'=> $tr->translate('Sign up'),
             'Already have an account? Login'=> $tr->translate('Already have an account? Login'),

         ];

         Cache::forever('registerStatics'.session()->get('locale'), $data);
         $registerStatics=Cache::get('registerStatics'.session()->get('locale'));

     }

//                  Cache::forget('registerStatics');
//                  Cache::forget('registerStatics');
@endphp

@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
             data-background="/frontendAssets/images/cargo.jpeg"
             style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{{ $registerStatics['Register'] }}</h2>
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
                        <input type="hidden" name="must_fill">
                        <div class="form-group mb-3">
                            <label for="number_of_cars">{{ $registerStatics['car_numbers'] }}</label>

                            <select name="number_of_cars" class="form-control" id="number_of_cars">
                                <option selected>5 {{ $registerStatics['cars'] }}</option>
                                <option>10 {{ $registerStatics['cars'] }}</option>
                                <option>20 {{ $registerStatics['cars'] }}</option>
                                <option>50 {{$registerStatics['cars'] }}</option>
                                <option>100 {{ $registerStatics['cars'] }}</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="company_name">{{ $registerStatics['Company Name (optional)'] }}</label>
                            <input type="text" value="{{ old('company_name') }}" class="form-control" id="company_name"
                                   name="company_name" placeholder="{{ $registerStatics['Enter your company name'] }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="personal_number">{{ $registerStatics['Company identification code / Personal ID'] }}</label>
                            <input type="text" value="{{ old('personal_number') }}" class="form-control"
                                   id="personal_number" name="personal_number"
                                   placeholder="{{ $registerStatics['Enter your Company identification code / Personal ID'] }}"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="contact_name">{{ $registerStatics['Full name'] }}</label>
                            <input type="text" class="form-control" value="{{ old('contact_name') }}" id="contact_name"
                                   name="contact_name" placeholder="{{ $registerStatics['Enter your Full name'] }}"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">{{ $registerStatics['Phone Number'] }}</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="{{ $registerStatics['Enter your phone number'] }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">{{ $registerStatics['Email Address'] }}</label>
                            <input type="email" value="{{ old('email') }}" class="form-control" id="email"
                                   name="email" placeholder="{{ $registerStatics['Enter your email address']}}"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{{ $registerStatics['Password'] }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="{{ $registerStatics['Enter a strong password'] }}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa fa-eye"></i> <!-- Font Awesome eye icon -->
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">

                            <label for="password_confirmation">{{ $registerStatics['Confirm password'] }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="{{ $registerStatics['Confirm your password'] }}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                        <i class="fa fa-eye"></i> <!-- Font Awesome eye icon -->
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember"
                                       required>
                                <label class="form-check-label"
                                       for="remember_me">{{ $registerStatics['I Agree to the'] }} <a
                                            href="{{route('terms')}}" target="_blank"><span
                                                style="color: blue;text-decoration:underline">{{ $registerStatics['Terms and Conditions'] }}</span>
                                    </a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ $registerStatics['Sign up'] }}</button>

                        <a href="{{ route('customer.login.get') }}"
                           style="font-size:12px">{{ $registerStatics['Already have an account? Login'] }}</a>

                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });

        document.getElementById('togglePassword2').addEventListener('click', function () {
            const passwordInput = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');

            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
