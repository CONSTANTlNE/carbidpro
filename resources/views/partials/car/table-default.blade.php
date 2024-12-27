@php
    use App\Services\CreditService;
use Carbon\Carbon  ;
    $creditService = new CreditService();

@endphp

<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
        <tr class="info">
            <th>ID</th>
            <th>CAR INFO</th>
            <th>FROM-TO</th>
            <th>
                <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'dispatcher.name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Broker
                </a>
            </th>
            <th>
                <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'customers.contact_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Dealer
                </a>
            </th>
            <th><a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'car_statuses.name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Status
                </a></th>
            <th>Balance</th>

            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cars as $index => $car)
            <tr>

                <td>
                    {{ $car->id }}</td>
                <td class="car_info">
                    @include('partials.car.table_content-parts.car-info')
                </td>
                {{--                <form action="{{ route('car.listupdate', $car->id) }}" method="POST">--}}
                @csrf
                <input type="hidden" name="status"
                       value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                <td>@include('partials.car.table_content-parts.field-from')</td>
                <td>
                    @if (!empty($car->dispatch))
                        {{ $car->dispatch->name }}
                    @endif
                </td>
                <td>
                    @if (!empty($car->customer))
                        {{ $car->customer->contact_name }}
                    @endif
                </td>
                <td>
                    @if (!empty($car->CarStatus))
                        <a
                                href="{{ route('car.showStatus', $car->CarStatus->slug) }}">{{ $car->CarStatus->name }}</a>
                    @endif

                </td>
                <td>
                    <strong>All Cost:</strong><br>
                    {{ $car->total_cost }}<br>
                    <strong>Amount due:</strong><br>
                    {{ $car->amount_due }}

                </td>

                <td>
                    <a href="{{ route('car.edit', $car->id) }}">
                        <button type="button" class="btn green_btn btn-sm">
                            Edit
                        </button>
                    </a>
                    <button type="button" class="btn {{$car->latestCredit? 'btn-warning' : 'btn-primary'}}  btn-sm"
                            data-toggle="modal"

                            data-target="   {{$car->latestCredit? '' : '#creditmodal'.$index}}">
                        {{$car->latestCredit? 'Credit Granted' : 'Add Credit'}}
                    </button>
                    {{--Credit modal--}}
                    <div class="modal fade" id="creditmodal{{$index}}" tabindex="-1" role="dialog"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <section>
                                    <form action="{{route('give.credit')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Give Credit</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label class="control-label">Amount:</label>
                                                    <input type="hidden" name="amount" value="{{ $car->amount_due}}">
                                                    <input type="hidden" name="car_id" value="{{ $car->id}}">
                                                    <input type="hidden" name="customer_id"
                                                           value="{{ $car->customer->id}}">
                                                    <input type="text" disabled
                                                           value="{{ $car->amount_due}}" class="form-control">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label class="control-label">Monthly %</label>
                                                    <input required name="percent" type="number" min="1" placeholder=""
                                                           class="form-control">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label class="control-label">Issue Date</label>
                                                    <input min="{{ $car->created_at->format('Y-m-d') }}"  required name="issue_date" type="date" placeholder="Invoice Date"
                                                           class="form-control">
                                                </div>

                                                <div class="col-md-12 form-group">
                                                    <label class="control-label">Comment</label>
                                                    <input name="comment" type="text" placeholder="Comment"
                                                           class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close
                                            </button>
                                            <button class="btn green_btn custom_grreen2">Save changes
                                            </button>
                                        </div>
                                    </form>
                                </section>
                            </div>
                        </div>
                    </div>
                    {{--Credit Info--}}
                    @if($car->latestCredit)
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#creditinfomodal{{$index}}">
                            Credit Info
                        </button>
                    @endif
                    {{--Credit modal--}}
                    <div class="modal fade " id="creditinfomodal{{$index}}" tabindex="-1" role="dialog"
                         aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content ">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Give Info</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-0 d-flex justify-content-center" style="overflow-x: auto; width: 100%;">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th class="p-1" style="width: 100px">Issue or Payment Date</th>
                                                    <th  class="p-1" style="width: 100px">Credit Amount <br> <span style="font-size: 12px">incl interest</span></th>
                                                    <th  class="p-1">Added Amount</th>
                                                    <th  class="p-1" style="width: 60px">Paid Amount</th>
                                                    <th  class="p-1" style="width: 80px">Daily Percent</th>
                                                    <th  class="p-1" style="width: 80px">Credit Days</th>
                                                    <th  class="p-1" style="width: 100px">Accrued interest</th>
                                                    <th  class="p-1">Comment</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($car->credit as $index2=> $credit)
                                                    <tr>
                                                        <td class="p-1">{{$credit->issue_or_payment_date->format('d-m-Y')}}</td>
                                                        <td  class="p-1">{{$credit->credit_amount}}</td>
                                                        <td  class="p-1" style="width: 60px!important">{{$credit->added_amount}}</td>
                                                        <td  class="p-1" style="width: 60px!important">{{$credit->paid_amount}}</td>
                                                        <td  class="p-1">
{{--                                                            {{round($credit->monthly_percent*12/365*1,4)}}--}}
                                                            {{round($credit->monthly_percent*12/365*1,4)}}
                                                        </td>
                                                        <td  class="p-1">{{$credit->credit_days}}</td>
                                                        <td  class="p-1">{{$credit->accrued_percent}}</td>
                                                        <td  class="p-1">{{$credit->comment}}</td>
                                                    </tr>
                                                @endforeach
                                                     <tr  style="background: #f2f2f2">
                                                         <td >Till Today</td>
{{--                                                         @dd(Carbon::parse($credit->issue_or_payment_date),Carbon::today())--}}
                                                         @if(Carbon::parse($credit->issue_or_payment_date)==Carbon::today())
                                                             <td></td>
                                                         @else
                                                             <td>{{$credit->credit_amount+round($creditService->totalInterestFromLastCalc($car->id),2)}}</td>
                                                         @endif

                                                         <td></td>
                                                         <td></td>
                                                         <td></td>

                                                         <td> {{$creditService->totalDaysFromLastCalcDate($car->id) }}</td>
                                                         <td > {{round($creditService->totalInterestFromLastCalc($car->id,),2) }}</td>
                                                         <td></td>
                                                     </tr>
                                                </tbody>
                                            </table>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>


                    {{--Delete Button--}}
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#deleteCarModal{{$index}}" data-car-id="{{ $car->id }}">
                        <i class="fa fa-trash-o"></i>
                    </button>
                    <div class="modal fade" id="deleteCarModal{{$index}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header modal-header-primary">
                                    <h3><i class="fa fa-user m-r-5"></i> Delete Car</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this Car?</p>
                                    <p>{{$car->vin}}</p>
                                    <form  action="{{ route('car.destroy', $car->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $car->id }}">
                                        <div class="form-group user-form-group">
                                            <div class="float-right">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        data-dismiss="modal">NO
                                                </button>
                                                <button type="submit" class="btn btn-add btn-sm">YES</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>
                    <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }}
                    <br>
                    <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>
                </td>
                {{--                </form>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- Delete Car Modal -->
<div class="modal fade" id="deleteCarModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <h3><i class="fa fa-user m-r-5"></i> Delete Car</h3>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Car?</p>
                <form id="deleteCarForm">
                    @csrf <!-- CSRF token for security -->
                    <input type="hidden" id="deleteCarId" name="car_id">
                    <!-- Hidden field to hold the user ID -->
                    <div class="form-group user-form-group">
                        <div class="float-right">
                            <button type="button" class="btn btn-danger btn-sm"
                                    data-dismiss="modal">NO
                            </button>
                            <button type="submit" class="btn btn-add btn-sm">YES</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
