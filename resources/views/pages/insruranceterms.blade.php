@extends('layouts.app')



@section('announces')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet"/>
    @include('partials.header')
    <!-- Left side column. contains the sidebar -->
    @include('partials.aside')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{--            <div class="header-icon">--}}
            {{--            <i class="fa fa-money"></i>--}}
            {{--            </div>--}}
            {{--            <div class="header-title">--}}
            {{--            <h1>Auctions</h1>--}}
            {{--            </div>--}}
        </section>
        <!-- Main content -->
        @if($errors->any())
            <div style="padding: 5px!important;"
                 class="ml-3 alert custom_alerts alert-danger alert-dismissible fade show w-25" role="alert">
                @foreach($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif
        <section style="margin-left: 15px" class="content">
            <div class="row">
                <div class="col-lg-12 pinpin">
                    <div class="card lobicard" id="lobicard-custom-controls" data-sortable="true">
                        <div class="card-header">
                            <div class="card-title custom_title">
                                <h1>Insurance Terms </h1>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="btn-group flex">
                                {{-- Location Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add Insurance
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add insurance</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('insurance.store')}}" method="post">
                                                @csrf
                                                <div class="modal-body">
{{--                                                    <div style="display: flex" class="flex justify-content-center mb-2">--}}
{{--                                                        <div class="form-group w-100 text-center">--}}
{{--                                                            <label class="text-center">Title</label>--}}
{{--                                                            <input name="title" type="text" class="form-control w-100"--}}
{{--                                                                   required="">--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div style="display: flex" class="flex justify-content-center mb-2">--}}
{{--                                                        <div class="form-group text-center">--}}
{{--                                                            <label class="text-center">Date</label>--}}
{{--                                                            <input name="date" type="date" class="form-control w-100"--}}
{{--                                                                   required="">--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                    <p class="text-center">Content</p>
                                                    <textarea style="display: none" name="insurancetext"
                                                              id="quill_content"></textarea>
                                                    <div id="editor">

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn green_btn custom_grreen2">
                                                        Create
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{--End Auction Create Modal--}}

                                {{-- Per Page--}}
{{--                                <form action="{{route('services.index')}}">--}}
{{--                                    <select style="width: 70px" class="ml-3 form-control" name="perpage" id=""--}}
{{--                                            onchange="this.form.submit()">--}}
{{--                                        <option value="10" {{request('perpage') == 10 ? 'selected' : ''}}>10</option>--}}
{{--                                        <option value="25" {{request('perpage') == 25 ? 'selected' : ''}}>25</option>--}}
{{--                                        <option value="50" {{request('perpage') == 50 ? 'selected' : ''}}>50</option>--}}
{{--                                        <option value="100" {{request('perpage') == 100 ? 'selected' : ''}}>100</option>--}}
{{--                                        <option value="500" {{request('perpage') == 500 ? 'selected' : ''}}>500</option>--}}
{{--                                    </select>--}}
{{--                                </form>--}}
                                {{-- Search--}}
{{--                                <form style="display: flex!important;" class="ml-3 "--}}
{{--                                      action="{{route('announcements.index')}}">--}}
{{--                                    <input type="text" name="search" class="form-control"--}}
{{--                                           value="{{request()->query('search')}}">--}}
{{--                                    <button type="submit"--}}
{{--                                            class="btn green_btn custom_grreen2 ml-2 mb-3 ">Search--}}
{{--                                    </button>--}}
{{--                                </form>--}}

{{--                                <form action="{{route('announcements.index')}}">--}}
{{--                                    <button type="submit" class="btn green_btn custom_grreen2 ml-2 mb-3 ">All--}}
{{--                                    </button>--}}
{{--                                </form>--}}

                            </div>
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Text</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($insuranceterms as $index=> $insuranceterm)
                                        <tr >
                                            <td class="ql-editor">{!! $insuranceterm->text !!} </td>
                                            <td>
                                                <form action="{{route('announcements.activate')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$insuranceterm->id}}">
                                                    @if($insuranceterm->is_active===1)
                                                        <button class="btn btn-success btn-rounded w-md m-b-5">
                                                            Active
                                                        </button>
                                                    @else
                                                        <button class="btn btn-danger btn-rounded w-md m-b-5">
                                                            Inactive
                                                        </button
                                                    @endif
                                                </form>
                                            </td>

                                            <td>
                                                {{--Edit Modal--}}
                                                {{--htmx returns initialized quill editor --}}
                                                <button hx-get="{{route('insuranse.update.htmx')}}"
                                                        hx-target="#target{{$index}}"
                                                        hx-vals='{"id": "{{$insuranceterm->id}}"}'
                                                        type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="update{{$index}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Update Insurance</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{route('insurance.update')}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{$insuranceterm->id}}">
                                                                <div id="target{{$index}}">

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                    <button type="submit" class="btn green_btn custom_grreen2">
                                                                        Update
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{--Delete Modal--}}
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#customer2{{$index}}"><i class="fa fa-trash-o"></i>
                                                </button>
                                                <div class="modal fade" id="customer2{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Insurance
                                                                </h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('announcements.delete')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$insuranceterm->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">
                                                                                        Delete insurance ?
                                                                                    </label>
                                                                                    <div class="flex justify-content-center mt-3">
                                                                                        <button type="button"
                                                                                                data-dismiss="modal"
                                                                                                class="btn btn-danger btn-sm">
                                                                                            NO
                                                                                        </button>
                                                                                        <button type="submit"
                                                                                                class="btn btn-add btn-sm">
                                                                                            YES
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
{{--                                @if($announcements->isEmpty())--}}
{{--                                    <div style="width: 100%;display: flex;justify-content: center">--}}
{{--                                        <span style="font-size: 20px" class="label label-pill label-danger m-r-15">No Records Found</span>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="flex justify-content-center">--}}
{{--                {{ $locations->links() }}--}}
{{--            </div>--}}
        </section>
        <!-- /.content -->
    </div>
    <!-- Initialize Quill editor -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const quill = new Quill('#editor', {
                modules: {
                    toolbar: [
                        [{header: [1, 2, false]}],
                        [{size: ['small', 'large', 'huge']}],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote'],
                        ['link', 'image', 'video'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                        [{ 'align': [] }],
                    ],
                },
                placeholder: 'Compose an epic...',
                theme: 'snow', // or 'bubble'
            });
            const textarea = document.getElementById('quill_content');

            quill.on('text-change', () => {
                textarea.value = quill.root.innerHTML
            })

            textarea.addEventListener('input', () => {
                quill.root.innerHTML = textarea.value
            })

        })

    </script>
@endsection