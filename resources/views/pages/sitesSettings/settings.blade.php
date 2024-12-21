@extends('layouts.app')



@section('settings')
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
                                <h1>Settings </h1>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="btn-group flex">
                                {{-- Location Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add New Settings
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog  modal-lg" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Settings</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('settings.store')}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div style="display: flex" class="flex justify-content-center mb-2">
                                                        <div class="form-group">
                                                            <label class="text-center">Label</label>
                                                            <input name="label" type="text" class="form-control"
                                                                   required="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="text-center">Key</label>
                                                            <input name="key" type="text" class="form-control"
                                                                   required="">
                                                        </div>
                                                    </div>
                                                    <p class="text-center">Text</p>
                                                    <textarea style="display: none" name="setting_value"
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
                                <form action="{{route('locations.index')}}">
                                    <select style="width: 70px" class="ml-3 form-control" name="perpage" id=""
                                            onchange="this.form.submit()">
                                        <option value="10" {{request('perpage') == 10 ? 'selected' : ''}}>10</option>
                                        <option value="25" {{request('perpage') == 25 ? 'selected' : ''}}>25</option>
                                        <option value="50" {{request('perpage') == 50 ? 'selected' : ''}}>50</option>
                                        <option value="100" {{request('perpage') == 100 ? 'selected' : ''}}>100</option>
                                        <option value="500" {{request('perpage') == 500 ? 'selected' : ''}}>500</option>
                                    </select>
                                </form>
                                {{-- Search--}}
                                <form style="display: flex!important;" class="ml-3 "
                                      action="{{route('locations.index')}}">
                                    <input type="text" name="search" class="form-control"
                                           value="{{request()->query('search')}}">
                                    <button type="submit"
                                            class="btn green_btn custom_grreen2 ml-2 mb-3 ">Search
                                    </button>
                                </form>

                            </div>
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Label</th>
                                        <th>Key</th>
                                        {{--                                        <th>Value</th>--}}
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($settings as $index=> $setting)
                                        <tr class="text-center">
                                            <td>{{$setting->label}}</td>
                                            <td>{{$setting->key}}</td>
                                            {{--                                            <td>{!! $setting->value !!}</td>--}}
                                            <td>
                                                {{--Edit Modal--}}
                                                <button hx-get="{{route('settings.update.htmx')}}"
                                                        hx-target="#target{{$index}}"
                                                        hx-vals='{"key": "{{$setting->key}}"}'
                                                        type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade " id="update{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content ">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">New Settings</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{route('settings.update')}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="key"
                                                                       value="{{$setting->key}}">
                                                                <div id="target{{$index}}">

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn green_btn custom_grreen2">
                                                                        Create
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
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
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Location
                                                                </h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('sliders.delete')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="key"
                                                                                   value="{{$setting->key}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Auction : {{$setting->label}}
                                                                                        ?</label>
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
                                @if($settings->isEmpty())
                                    <div style="width: 100%;display: flex;justify-content: center">
                                        <span style="font-size: 20px" class="label label-pill label-danger m-r-15">No Records Found</span>
                                    </div>
                                @endif
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

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <!-- Initialize Quill editor -->
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
                        [ 'code-block'],
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