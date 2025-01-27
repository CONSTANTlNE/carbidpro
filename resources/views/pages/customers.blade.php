@extends('layouts.app')
@php
    use App\Services\CreditService;
    use Carbon\Carbon;

    $creditService=new CreditService();

@endphp


@section('customers')
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
                                <h1>{{request()->query('archive') ? 'Archived' : 'Active'}} Customers {{$count}}</h1>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="btn-group flex">
                                {{-- Add new Customer Modal--}}
                                @if(!request()->query('archive'))
                                    <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                            data-target="#mymodals">
                                        Add New Customer
                                    </button>
                                @endif
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Customer</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('customer.register.post')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="admin" value="admin">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Company Name</label>
                                                            <input name="company_name" required type="text"
                                                                   placeholder="" class="form-control"
                                                                   value="{{old('company_name')}}">
                                                        </div>
                                                        <!-- Text input-->
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Company/Personal ID</label>
                                                            <input name="personal_number" required type="text"
                                                                   placeholder=""
                                                                   class="form-control"
                                                                   value="{{old('personal_number')}}">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Contact Person</label>
                                                            <input name="contact_name" required type="text"
                                                                   placeholder="" class="form-control"
                                                                   value="{{old('contact_name')}}">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Phone</label>
                                                            <input name="phone" required type="text" placeholder=""
                                                                   value="{{old('phone')}}"
                                                                   class="form-control">
                                                        </div>
                                                        <!-- Text input-->
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Email</label>
                                                            <input name="email" required type="text" placeholder=""
                                                                   class="form-control" value="{{old('email')}}">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Car purchase per Month</label>
                                                            <select name="number_of_cars" id="" class="form-control">
                                                                <option {{old('number_of_cars')==10 ? 'selected' : ''}} value="10">
                                                                    10
                                                                </option>
                                                                <option {{old('number_of_cars')==20 ? 'selected' : ''}} value="20">
                                                                    20
                                                                </option>
                                                                <option {{old('number_of_cars')==50 ? 'selected' : ''}} value="50">
                                                                    50
                                                                </option>
                                                                <option {{old('number_of_cars')==100 ? 'selected' : ''}} value="100">
                                                                    100 +
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row border-bottom">

                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Extra For Calculator</label>
                                                            <input name="extra_for_team" required type="text"
                                                                   placeholder=""
                                                                   class="form-control"
                                                                   value="{{old('extra_for_team')}}">
                                                        </div>

                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">password</label>
                                                            <input name="password" required type="text" placeholder=""
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">confirm password</label>
                                                            <input name="password_confirmation" required type="text"
                                                                   placeholder=""
                                                                   class="form-control">
                                                        </div>

                                                    </div>
                                                    <p style="font-weight: bolder" class="text-center mt-3">Extra
                                                        Expenses</p>
                                                    <div class="row">
                                                        @foreach($extraexpences as $extraexpence)
                                                            <div class="col-md-4 form-group">
                                                                <label class="control-label">{{$extraexpence->name}}</label>
                                                                <input name="{{$extraexpence->name}}" type="text"
                                                                       placeholder=""
                                                                       class="form-control"
                                                                       value="{{old($extraexpence->name)}}">
                                                            </div>
                                                        @endforeach
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


                                {{-- Per Page--}}
                                <form action="{{route('customers.index')}}">
                                    <select style="width: 70px" class="ml-3 form-control" name="perpage" id=""
                                            onchange="this.form.submit()">
                                        <option value="50" {{request('perpage') == 50 ? 'selected' : ''}}>50</option>
                                        <option value="90" {{request('perpage') == 90 ? 'selected' : ''}}>90</option>
                                        <option value="150" {{request('perpage') == 150 ? 'selected' : ''}}>150</option>
                                    </select>
                                </form>
                                {{-- Search--}}
                                <form style="display: flex!important;" class="ml-3 "
                                      action="{{route('customers.index')}}">
                                    {{--Keep query strings--}}
                                    @foreach(request()->query() as $key => $value)
                                        @if($key != 'search')
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endif
                                    @endforeach
                                    <input type="text" name="search" class="form-control"
                                           value="{{request()->query('search')}}">
                                    <button type="submit"
                                            class="btn green_btn custom_grreen2 ml-2 mb-3 ">Search
                                    </button>
                                </form>
                                {{--Return All Data--}}
                                @if(request()->query('search'))
                                    <form action="{{route('customers.index')}}">
                                        {{--Keep query strings--}}
                                        @foreach(request()->query() as $key => $value)
                                            @if($key != 'search')
                                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                            @endif
                                        @endforeach
                                        <button type="submit"
                                                class="btn green_btn custom_grreen2 ml-2 mb-3 ">All
                                        </button>
                                    </form>
                                @endif
                                @if(!request()->query('archive'))
                                    @if(isset($trashedCount))
                                        <a href="{{route('customers.index', ['archive' => 'archive'])}}" type="button"
                                           class="btn btn-danger custom_grreen2 ml-2 mb-3 ">
                                            Archived {{$trashedCount}}
                                        </a>
                                    @endif
                                @endif
                            </div>

                            <div style="overflow: auto;max-height: 600px!important ">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Company Name</th>
                                        <th>Contact Person</th>
                                        <th>Amount Due</th>
                                        <th>Deposit</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Main Dealer</th>
                                        <th>Extra</th>
                                        <th>Login</th>
                                        <th>Status</th>
                                        <th>Crated At</th>
                                        @if(request()->query('archive'))
                                            <th>Archived At</th>
                                        @endif
                                        <th>Crated Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customers as $index=> $customer)
                                        <tr class="text-center">
                                            <td>{{$customer->company_name}}</td>
                                            <td>{{$customer->contact_name}}</td>
                                            <td>
                                                @php
                                                    $totalAmountDue = 0;
                                                @endphp
                                                @foreach ($customer->cars as $key => $car)
                                                    {{--Calculate Total Amount Due--}}
                                                    @if($car->latestCredit)
                                                        @php
                                                            $totalAmountDue += round($car->latestCredit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))
                                                        @endphp
                                                    @else
                                                        @php
                                                            $totalAmountDue += round( $car->amount_due)
                                                        @endphp
                                                    @endif
                                                    @if($loop->last)
                                                        <div class="w-100 d-flex justify-content-center">
                                                            <p class="btn btn-danger">{{$totalAmountDue}} $</p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($customer->balances->isNotEmpty())
                                                    <div class="w-100 d-flex justify-content-center">
                                                        <p class="btn btn-success"> {{$customer->balances->sum('amount')}}
                                                            $</p>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{$customer->phone}}</td>
                                            <td>{{$customer->email}}</td>
                                            <td>{{$customers->where('id',$customer->child_of)->first()?->contact_name}}</td>
                                            <td>{{$customer->extra_for_team}}</td>
                                            <td>
                                                <form action="{{route('customers.autologin')}}" method="post"
                                                      target="_blank">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{$customer->id}}">
                                                    <button class="btn btn-primary">Login
                                                        @if($customer->created_at < now())
                                                            (new)
                                                        @endif
                                                    </button>
                                                </form>
                                                @if($customer->created_at < now())
                                                    <form action="{{route('generate.link')}}" method="get"
                                                          target="_blank" class="mt-3">
                                                        @csrf
                                                        <input type="hidden" name="customer_id"
                                                               value="{{$customer->id}}">
                                                        <button class="btn btn-primary">Login (old)</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{route('customer.activate')}}" method="post" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$customer->id}}">
                                                    @if($customer->is_active==1)
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
                                            <td>{{$customer->created_at?->format('d-m-Y')}}</td>
                                            @if(request()->query('archive'))
                                                <td>{{$customer->deleted_at?->format('d-m-Y')}}</td>
                                            @endif
                                            <td>

                                                {{--Edit Modal--}}
                                                <button type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3> Edit customer</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <form action="{{route('customers.update' )}}"
                                                                  method="post">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                       value="{{$customer->id}}">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">Company
                                                                                Name</label>
                                                                            <input name="company_name"
                                                                                   type="text"
                                                                                   placeholder="" class="form-control"
                                                                                   value="{{$customer->company_name}}">
                                                                        </div>
                                                                        <!-- Text input-->
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">Company/Personal
                                                                                ID</label>
                                                                            <input name="personal_number" required
                                                                                   type="text" placeholder=""
                                                                                   class="form-control"
                                                                                   value="{{$customer->personal_number}}">
                                                                        </div>
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">Contact
                                                                                Person</label>
                                                                            <input name="contact_name" required
                                                                                   type="text"
                                                                                   placeholder="" class="form-control"
                                                                                   value="{{$customer->contact_name}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">Phone</label>
                                                                            <input name="phone" required type="text"
                                                                                   placeholder=""
                                                                                   class="form-control"
                                                                                   value="{{$customer->phone}}">
                                                                        </div>
                                                                        <!-- Text input-->
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">Email</label>
                                                                            <input name="email" required type="text"
                                                                                   placeholder=""
                                                                                   class="form-control"
                                                                                   value="{{$customer->email}}">
                                                                        </div>
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">Car purchase
                                                                                per Month</label>
                                                                            <select name="number_of_cars" id=""
                                                                                    class="form-control">
                                                                                <option @selected($customer->number_of_cars == 10) value="10">
                                                                                    10
                                                                                </option>
                                                                                <option @selected($customer->number_of_cars == 20) value="20">
                                                                                    20
                                                                                </option>
                                                                                <option @selected($customer->number_of_cars == 50) value="50">
                                                                                    50
                                                                                </option>
                                                                                <option @selected($customer->number_of_cars == 100) value="100">
                                                                                    100 +
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row border-bottom">
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">Extra For
                                                                                Calculator</label>
                                                                            <input name="extra_for_team" required
                                                                                   type="text" placeholder=""
                                                                                   class="form-control"
                                                                                   value="{{$customer->extra_for_team}}">
                                                                        </div>
                                                                        <div class="col-md-4 form-group">
                                                                            <label class="control-label">password</label>
                                                                            <input name="password" type="text"
                                                                                   placeholder=""
                                                                                   class="form-control">
                                                                        </div>

                                                                    </div>
                                                                    <p style="font-weight: bolder"
                                                                       class="text-center mt-3">Extra
                                                                        Expenses</p>
                                                                    <div class="row">
                                                                        @php
                                                                            $customerextraexpense = json_decode($customer->extra_expenses, true);
                                                                        @endphp
                                                                        @foreach($extraexpences as $index10 => $extraexpence)
                                                                            <div class="col-md-4 form-group">
                                                                                <label class="control-label">{{$extraexpence->name}}</label>
                                                                                <input name="{{$extraexpence->name}}"
                                                                                       type="text"
                                                                                       placeholder=""
                                                                                       class="form-control"
                                                                                       @if($customer->extra_expenses != null)
                                                                                           value="{{array_key_exists($extraexpence->name,$customerextraexpense)?$customerextraexpense[$extraexpence->name]:''}}"
                                                                                       @endif
                                                                                >
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button"
                                                                            class="btn btn-danger float-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn green_btn custom_grreen2">
                                                                        Update
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
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Customer
                                                                </h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('customers.delete')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$customer->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Customer
                                                                                        : {{$customer->contact_name}}
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
                                                {{--Restore customer--}}
                                                @if(request()->has('archive'))
                                                    <form style="all: unset"
                                                          action="{{route('customers.restore', ['id' =>$customer->id])}}">
                                                        <button class="btn btn-success btn-sm">
                                                            <i class="fa fa-refresh"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if($customers->isEmpty())
                                    <div style="width: 100%;display: flex;justify-content: center">
                                        <span style="font-size: 20px" class="label label-pill label-danger m-r-15">No Records Found</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-content-center">
                {{ $customers->links() }}
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection