@extends('frontend.layout.app')

@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/assets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{!! $tr->translate('Login') !!}</h2>
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
                            <label for="email">{!! $tr->translate('Email') !!} </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="{!! $tr->translate('Enter your email') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{!! $tr->translate('Password') !!}</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{!! $tr->translate('Enter your password') !!}" required>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">{!! $tr->translate('Remember Me') !!}</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{!! $tr->translate('Log in') !!}</button>

                        <a href="{{ route('customer.register.get') }}" class="btn btn-secondary">{!! $tr->translate('Sign up') !!}</a>

                    </form>
                </div>

                <br>

            </div>
        </div>
    </div>
@endsection
