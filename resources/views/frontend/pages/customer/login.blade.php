@extends('frontend.layout.app')

@php


    $loginStatics=Cache::get('loginStatics'.session()->get('locale'));

              if($loginStatics===null){

                  $data=[
                      'Email'=>$tr->translate('Email'),
                      'Enter your email'=>$tr->translate('Enter your email'),
                      'Contact'=>$tr->translate('Contact'),
                      'Password'=>$tr->translate('Password'),
                      'Enter your password'=>$tr->translate('Enter your password'),
                      'Remember Me'=>$tr->translate('Remember Me'),
                      'Log in'=>$tr->translate('Log in'),
                      'Sign up'=>$tr->translate('Sign up'),
                      'Login'=>$tr->translate('Login'),

                  ];

                  Cache::forever('loginStatics'.session()->get('locale'), $data);

              }

//                  Cache::forget('loginStaticsen');
//                  Cache::forget('loginStaticsru');


 @endphp



@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/frontendAssets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{{ Cache::get('loginStatics' . session()->get('locale'))['Login'] }}</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <br>
                <div class="auth_page_wrapper">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {!! $tr->translate(session('status')) !!}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{!! $tr->translate($error) !!} </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('customer.login.post') }}" method="post">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email">{{ Cache::get('loginStatics' . session()->get('locale'))['Email'] }} </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="{{ Cache::get('loginStatics' . session()->get('locale'))['Enter your email'] }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{{ Cache::get('loginStatics' . session()->get('locale'))['Password'] }}</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{{ Cache::get('loginStatics' . session()->get('locale'))['Enter your password'] }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">{{ Cache::get('loginStatics' . session()->get('locale'))['Remember Me'] }}</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ Cache::get('loginStatics' . session()->get('locale'))['Log in'] }}</button>

                        <a href="{{ route('customer.register.get') }}" class="btn btn-secondary">{{ Cache::get('loginStatics' . session()->get('locale'))['Sign up'] }}</a>

                    </form>
                </div>

                <br>

            </div>
        </div>
    </div>
@endsection
