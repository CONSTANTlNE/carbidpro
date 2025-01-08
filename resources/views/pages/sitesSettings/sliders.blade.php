@extends('layouts.app')



@section('sliders')
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
                                <h1>Sliders </h1>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="btn-group flex">
                                {{-- Slider Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add New Slider
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Slider</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('sliders.store')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div  class="col-md-12 form-group">
                                                        <label class="control-label">Title</label>
                                                        <input  type="text" name="title"
                                                                class="form-control mb-3" required>

                                                    </div>
                                                    <div  class="col-md-12 form-group">
                                                        <label class="control-label">Image</label>

                                                        <input accept="image/jpeg,image/png,image/webp,image/jpg"  type="file" name="image"
                                                               class="input-file mb-3" required>

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

                            </div>
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sliders as $index=> $slider)
                                        <tr class="text-center">
                                            <td>{{$slider->title}}</td>
                                            <td>
                                                <form action="{{route('sliders.activate')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$slider->id}}">
                                                    @if($slider->is_active===1)
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

                                            <td>{{$slider->created_at->format('d-m-Y')}}</td>
                                            <td>
                                                {{--Edit Modal--}}
                                                <button type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3> Edit Slider</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('sliders.update')}}"
                                                                              method="post" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$slider->id}}" id="">
                                                                            <div class="row flex justify-content-center">
                                                                                <div  class="col-md-12 form-group">
                                                                                    <label class="control-label">Title</label>
                                                                                    <input  type="text" name="title" value="{{$slider->title}}"
                                                                                            class="form-control mb-3" required>

                                                                                </div>
                                                                                <div  class="col-md-12 form-group">
                                                                                    <label class="control-label">Image</label>

                                                                                    <input accept="image/jpeg,image/png,image/webp,image/jpg"  type="file" name="image"
                                                                                           class="input-file mb-3" required>

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
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Slider
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
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$slider->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Slider : {{$slider->title}} ?
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
                                @if($sliders->isEmpty())
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

@endsection