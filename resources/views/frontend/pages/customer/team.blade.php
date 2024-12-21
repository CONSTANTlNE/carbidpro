@extends('frontend.layout.app')

{{--@dd($teams , auth()->user()->id)--}}
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
                <a href="{{ route('customer.addTeam') }}" class="btn btn-success mb-2" style="color:#fff">
                    {!! $tr->translate('Add Team member') !!}
                    </a>
                <table id="myTable" class="display">
                    <thead>
                        <tr>
                            <th>{!! $tr->translate('Name') !!}</th>
                            <th>{!! $tr->translate('Email') !!}</th>
                            <th>{!! $tr->translate('Phone') !!}</th>
                            <th>{!! $tr->translate('Active') !!}</th>
                            <th>{!! $tr->translate('Action') !!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                            <tr>
                                <td>{{ $team->contact_name }}</td>
                                <td>{{ $team->email }}</td>
                                <td>{{ $team->phone }}</td>
                                <td>{!! $team->is_active == 1 ? '<span class="true">YES</span>' : '<span class="false">NO</span>' !!}</td>
                                <td>
                                    <div class="row" style="gap: 5px">
                                        <a href="{{ route('customer.teamEdit', $team->id) }}">
                                            <button class="btn btn-warning" type="submit">
                                                {!! $tr->translate('Edit') !!}</button>
                                        </a>

                                        <form action="{{ route('customer.removeTeam', $team->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-danger" type="submit">
                                                {!! $tr->translate('Delete') !!}</button>
                                        </form>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>


                <br>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
        <script>
            let table = new DataTable('#myTable', {
                columnDefs: [{
                    width: 65,
                    targets: 0
                }, {
                    width: 180,
                    targets: 4
                }, {
                    width: 150,
                    targets: 11
                }],
                scrollX: true
            });
        </script>
    @endpush
@endsection
