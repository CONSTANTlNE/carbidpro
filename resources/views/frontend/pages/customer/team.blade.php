@extends('frontend.layout.app')

@php

       $teamStatics=Cache::get('teamStatics'.session()->get('locale'));

                 if($teamStatics===null){

                     $data=[
                         'Add Team'=>$tr->translate('Add Team'),
                         'Add Team member'=>$tr->translate('Add Team member'),
                         'Name'=>$tr->translate('Name'),
                         'Email'=>$tr->translate('Email'),
                         'Phone'=>$tr->translate('Phone'),
                         'Date'=>$tr->translate('Date'),
                         'Active'=>$tr->translate('Active'),
                         'Action'=>$tr->translate('Action'),
                         'Edit'=>$tr->translate('Edit'),
                         'Delete'=>$tr->translate('Delete'),


                     ];
                     Cache::forever('teamStatics'.session()->get('locale'), $data);
                     $teamStatics=Cache::get('teamStatics'.session()->get('locale'));
                 }
   //
   //                  Cache::forget('teamStaticsen');
   //                  Cache::forget('teamStaticsru');

@endphp


@section('content')
    @push('style')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <style>
            .container {
                max-width: 1700px;
            }

            table, th, td {
                border: 1px solid;
            }

            td {
                padding: 7px;
            }
        </style>
    @endpush
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
             data-background="/frontendAssets/images/cargo.jpeg"
             style="background-image: url(&quot;/assets/images/cargo.jpeg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 80px;padding: 0;">{{ $teamStatics['Add Team'] }}</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8" style="overflow-x: auto;">
                        <a href="{{ route('customer.addTeam') }}" class="btn btn-success mb-2" style="color:#fff">
                            {{ $teamStatics['Add Team member'] }}
                        </a>
                        <table  id="myTable" class="display m-auto" style="width:100%!important" >
                            <thead>
                            <tr class="text-center">
                                <th class="text-center">   {{ $teamStatics['Name'] }}</th>
                                <th class="text-center">   {{ $teamStatics['Email'] }}</th>
                                <th class="text-center">   {{ $teamStatics['Phone'] }}</th>
                                <th class="text-center">   {{ $teamStatics['Active'] }}</th>
                                <th class="text-center">   {{ $teamStatics['Action'] }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($teams as $team)
                                <tr>
                                    <td class="text-center">{{ $team->contact_name }}</td>
                                    <td class="text-center">{{ $team->email }}</td>
                                    <td class="text-center">{{ $team->phone }}</td>
                                    <td class="text-center">{!! $team->is_active == 1 ? '<span class="true">YES</span>' : '<span class="false">NO</span>' !!}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center" style="gap: 5px">
                                            <a href="{{ route('customer.teamEdit', $team->id) }}">
                                                <button class="btn btn-warning" type="submit">
                                                    {{ $teamStatics['Edit'] }}
                                                </button>
                                            </a>

                                            <form action="{{ route('customer.removeTeam', $team->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-danger" type="submit">
                                                    {{ $teamStatics['Delete'] }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
        <script>

            let table = new DataTable('#myTable');
            parentDiv =document.getElementById('myTable').parentElement.style.width='100%';



        </script>
    @endpush
@endsection
