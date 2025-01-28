@extends('layouts.app')



@section('smsdrafts')
    @include('partials.header')
    <!-- =============================================== -->
    <!-- Left side column. contains the sidebar -->
    @include('partials.aside')
    @push('css')
        <link href="{{asset('assets/plugins/modals/component.css')}}" rel="stylesheet"/>
    @endpush
    <div class="content-wrapper">

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
                                <h1>SMS Drafts</h1>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="btn-group">
                                {{-- Auction Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add New Draft
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Draft</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('sms.drafts.store')}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="col-md-12 form-group">
                                                        <input type="text" name="action_name"
                                                               required placeholder="Draft Key (For Developer)" class="form-control">
                                                    </div>
                                                    <div class="col-md-12 form-group">
                                                        <input type="text" name="action_description"
                                                               required placeholder="Draft Description"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-md-12 form-group">
                                                        <textarea style="font-size: 20px" required name="draft"
                                                                  class="form-control" rows="10"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn green_btn custom_grreen2">Create
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
                                        <th>Draft For</th>
                                        <th>Draft Description</th>
                                        <th>Draft</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($drafts as $index=> $draft)
                                        <tr class="text-center">
                                            <td>{{$draft->action_name}}</td>
                                            <td>{{$draft->action_description}}</td>
                                            <td>{{$draft->draft}}</td>
                                            <td>
                                                <form action="{{route('sms.drafts.activate')}}" method="post"
                                                      novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$draft->id}}">
                                                    @if($draft->is_active==1)
                                                        <button type="submit"
                                                                class="btn btn-success btn-rounded w-md m-b-5">Active
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                                class="btn btn-danger btn-rounded w-md m-b-5">Inactive
                                                        </button
                                                    @endif
                                                </form>
                                            </td>
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
                                                                <h3> Edit Auction</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('sms.drafts.update')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$draft->id}}" id="">
                                                                            <div class="row flex justify-content-center">

                                                                                <div class="col-md-12 form-group">
                                                                                    <input type="text"
                                                                                           name="action_description"
                                                                                           required
                                                                                           placeholder="Draft Description"
                                                                                           class="form-control" value="{{$draft->action_description}}">
                                                                                </div>
                                                                                <div class="col-md-12 form-group">
                                                                                    <textarea style="font-size: 20px"
                                                                                              required name="draft"
                                                                                              class="form-control"
                                                                                              rows="10">{{$draft->draft}}</textarea>
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
                                                    </div>
                                                </div>
                                                    {{--Delete Modal--}}
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#customer2{{$index}}"><i
                                                                class="fa fa-trash-o"></i>
                                                    </button>
                                                    <div class="modal fade" id="customer2{{$index}}" tabindex="-1"
                                                         role="dialog" style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-user m-r-5"></i> Delete SMS Draft
                                                                    </h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <form class="form-horizontal"
                                                                                  action="{{route('sms.drafts.delete')}}"
                                                                                  method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="id"
                                                                                       value="{{$draft->id}}">
                                                                                <fieldset>
                                                                                    <div class="col-md-12 form-group user-form-group">
                                                                                        <label class="control-label">Delete
                                                                                            Draft : {{$draft->action_name}}
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal js -->
    <script src="{{asset('assets/plugins/modals/classie.js')}}"></script>
    <script src="{{asset('assets/plugins/modals/modalEffects.js')}}"></script>
    <!-- End Page Lavel Plugins


@endsection