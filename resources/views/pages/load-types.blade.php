@extends('layouts.app')



@section('load-types')
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
                        <div  class="card-header">
                            <div class="card-title custom_title">
                                <h2>Load Types </h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="btn-group flex">
                                {{-- Location Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add New Type
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Type</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('load-types.store')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div style="display: flex" class="flex">
                                                        <input required type="text" name="name"
                                                               placeholder="Location Name" class="form-control mb-3">
                                                        <input required style="max-width: 100px"  type="number" min="1" name="price"
                                                               placeholder="Price" class="form-control mb-3 ml-2">
                                                    </div>
                                                    <input style="padding-top: 3px!important;" type="file" name="icon"
                                                           placeholder="icon" class="form-control ">
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
{{--                                <form action="{{route('locations.index')}}">--}}
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
{{--                                      action="{{route('locations.index')}}">--}}
{{--                                    <input type="text" name="search" class="form-control"--}}
{{--                                           value="{{request()->query('search')}}">--}}
{{--                                    <button type="submit"--}}
{{--                                            class="btn green_btn custom_grreen2 ml-2 mb-3 ">Search--}}
{{--                                    </button>--}}
{{--                                </form>--}}
                            </div>
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>price</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($loadTypes as $index=> $type)
                                        <tr class="text-center">
                                            <td style="vertical-align: middle">{{$type->name}}</td>
                                            <td>
                                                <img style="width: 100px" src=" {{ Storage::url($type->icon) }}" alt="">
                                            </td>
                                            <td style="vertical-align: middle">{{$type->price}}</td>
                                            <td style="vertical-align: middle">{{$type->created_at->format('d-m-Y')}}</td>
                                            <td style="vertical-align: middle">
                                                {{--Edit Modal--}}
                                                <button type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3> Edit Location</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('load-types.update')}}"
                                                                              method="post" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$type->id}}" id="">
                                                                            <div class="row flex justify-content-center">
                                                                                <div class="col-md-6 form-group">
                                                                                    <label class="control-label">Name</label>
                                                                                    <input type="text"
                                                                                           name="name"
                                                                                           value="{{$type->name}}"
                                                                                           class="form-control">

                                                                                </div>
                                                                                <div class="col-md-3 form-group">
                                                                                    <label class="control-label">Price</label>
                                                                                    <input name="price" min="1" type="number"
                                                                                           class="form-control" value="{{$type->price}}">
                                                                                </div>
                                                                                <div class="col-md-4 form-group">
                                                                                    <input type="file" name="icon">
                                                                                </div>

                                                                                <div class="col-md-12 form-group user-form-group mt-3">
                                                                                    <div class="flex justify-content-center">
                                                                                        <button type="button"
                                                                                                data-dismiss="modal"
                                                                                                class="btn btn-danger btn-sm">
                                                                                            Cancel
                                                                                        </button>
                                                                                        <button type="submit"
                                                                                                class="btn btn-add btn-sm">
                                                                                            Update
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{--                                                            <div class="modal-footer">--}}
                                                            {{--                                                                <button type="button" class="btn btn-danger float-left"--}}
                                                            {{--                                                                        data-dismiss="modal">Close--}}
                                                            {{--                                                                </button>--}}
                                                            {{--                                                            </div>--}}
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
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Load Type
                                                                </h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('load-types.destroy')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$type->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Load Type : {{$type->name}}
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
                                @if($loadTypes->isEmpty())
                                    <div style="width: 100%;display: flex;justify-content: center">
                                        <span style="font-size: 20px" class="label label-pill label-danger m-r-15">No Records Found</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>

@endsection