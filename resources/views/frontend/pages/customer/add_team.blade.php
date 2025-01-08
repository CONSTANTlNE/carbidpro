@extends('frontend.layout.app')

@php
    use Carbon\Carbon;

//                     Cache::forget('addTeamStaticsen');
//                     Cache::forget('addTeamStaticsru');

       $addTeamStatics=Cache::get('addTeamStatics'.session()->get('locale'));

                 if($addTeamStatics===null){

                     $data=[
                         'Add Team'=>$tr->translate('Add Team'),
                         'Role'=>$tr->translate('Role'),
                         'Contact Name'=>$tr->translate('Contact Name'),
                         'Dealer'=>$tr->translate('Dealer'),
                         'Enter your contact name'=>$tr->translate('Enter your contact name'),
                         'Email Address'=>$tr->translate('Email Address'),
                         'Enter your email address'=>$tr->translate('Enter your email address'),
                         'Phone'=>$tr->translate('Phone'),
                         'Enter your Phone'=>$tr->translate('Enter your Phone'),
                         'Extra for Calculator'=>$tr->translate('Extra for Calculator'),
                         'Enter your Extra'=>$tr->translate('Enter your Extra'),
                         'Enter a strong password'=>$tr->translate('Enter a strong password'),
                         'Password'=>$tr->translate('Password'),
                         'Confirm Password'=>$tr->translate('Confirm Password'),
                         'Confirm your password'=>$tr->translate('Confirm your password'),
                         'Add'=>$tr->translate('Add'),
                     ];
                     Cache::forever('addTeamStatics'.session()->get('locale'), $data);
                     $addTeamStatics=Cache::get('addTeamStatics'.session()->get('locale'));
                 }



@endphp


@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/frontendAssets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{{ $addTeamStatics['Add Team'] }}</h2>

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
                            <label for="">{{ $addTeamStatics['Role'] }}</label> <br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="dealer" id="yes" checked name="role"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="yes">{{ Cache::get('addTeamStatics' . session()->get('locale'))['Dealer'] }}</label>
                            </div>
                            

                        </div>

                        <input type="hidden" name="team" value="1">

                        <div class="form-group mb-3">
                            <label for="contact_name">{{ $addTeamStatics['Contact Name'] }}</label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name"
                                placeholder="{{ $addTeamStatics['Enter your contact name'] }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">{{ $addTeamStatics['Email Address'] }} </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="{{$addTeamStatics['Enter your email address'] }}" required>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="phone">{{ $addTeamStatics['Phone'] }} </label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                placeholder="{{ $addTeamStatics['Enter your Phone'] }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="extra_for_team">{{$addTeamStatics['Extra for Calculator'] }}</label>
                            <input type="number" class="form-control" id="extra_for_team" name="extra_for_team"
                                placeholder="{{$addTeamStatics['Enter your Extra'] }}" value="0">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{{ $addTeamStatics['Password'] }}</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{{ $addTeamStatics['Enter a strong password'] }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">{{ $addTeamStatics['Confirm Password'] }}</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="{{ $addTeamStatics['Confirm your password'] }}" required>
                        </div>


                        <button type="submit" class="btn btn-primary">{{ $addTeamStatics['Add'] }}</button>

                    </form>
                </div>

                <br>

            </div>
        </div>
    </div>
@endsection
