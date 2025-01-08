@extends('frontend.layout.app')

@php


//                     Cache::forget('addTeamStaticsen');
//                     Cache::forget('addTeamStaticsru');

       $editTeamStatics=Cache::get('editTeamStatics'.session()->get('locale'));

                 if($editTeamStatics===null){

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
                         'Update'=>$tr->translate('Update'),
                     ];

                     Cache::forever('editTeamStatics'.session()->get('locale'), $data);
                     $editTeamStatics=Cache::get('editTeamStatics'.session()->get('locale'));
                 }

@endphp


@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="/frontendAssets/images/cargo.jpeg" style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{{$editTeamStatics['Add Team']}}</h2>
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

                    <form action="{{ route('customer.teamUpdate', $team->id) }}" method="post">
                        @csrf


                        <div class="form-group mb-3">
                            <label for="">{{$editTeamStatics['Role']}}</label> <br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="dealer" checked id="yes" name="role"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="yes">{{$editTeamStatics['Dealer']}}</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="contact_name">{{$editTeamStatics['Contact Name']}}</label>
                            <input type="text" value="{{ $team->contact_name }}" class="form-control" id="contact_name"
                                name="contact_name" placeholder="{{$editTeamStatics['Enter your contact name']}}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">{{$editTeamStatics['Email Address']}} </label>
                            <input type="email" value="{{ $team->email }}" class="form-control" id="email"
                                name="email" placeholder="{{$editTeamStatics['Enter your email address']}}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">{{$editTeamStatics['Phone']}}</label>
                            <input type="tel" value="{{ $team->phone }}" class="form-control" id="phone"
                                name="phone" placeholder="{{$editTeamStatics['Enter your Phone']}}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="extra_for_team">{{$editTeamStatics['Extra for Calculator']}} </label>
                            <input type="number" class="form-control" id="extra_for_team" name="extra_for_team"
                                placeholder="{{$editTeamStatics['Enter your Extra']}}" value="{{ $team->extra_for_team }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{{$editTeamStatics['Password']}} </label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{{$editTeamStatics['Enter a strong password']}}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">{{$editTeamStatics['Confirm Password']}}</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="{{$editTeamStatics['Confirm your password']}}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Is Active</label> <br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="1" id="yes" name="is_active"
                                    class="custom-control-input" {{ $team->is_active == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="yes">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="0" id="no" name="is_active"
                                    {{ $team->is_active == 0 ? 'checked' : '' }} class="custom-control-input">
                                <label class="custom-control-label" for="no">No</label>
                            </div>

                        </div>


                        <button type="submit" class="btn btn-primary">{{$editTeamStatics['Update']}}</button>

                    </form>
                </div>

                <br>

            </div>
        </div>
    </div>
@endsection
