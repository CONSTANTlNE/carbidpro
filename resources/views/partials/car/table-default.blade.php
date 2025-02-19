@php
    use App\Services\CreditService;
use Carbon\Carbon  ;
    $creditService = new CreditService();

@endphp

<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
        <tr class="info">
            <th class="text-center">ID</th>
            <th class="text-center">CAR INFO</th>
            <th class="text-center">FROM-TO</th>
            <th class="text-center">
                <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'dispatcher.name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Broker
                </a>
            </th>
            <th class="text-center">
                <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'customers.contact_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Dealer
                </a>
            </th>
            <th class="text-center"><a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'car_statuses.name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Status
                </a></th>
            <th class="text-center">Balance</th>

            <th style="min-width: 230px!important;" class="text-center">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cars as $index => $car)
            <tr>
                <td>{{ $car->id }}</td>
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
                        <a href="{{ route('car.showStatus', $car->CarStatus->slug) }}">{{ $car->CarStatus->name }}</a>
                    @endif
                </td>
                <td>
                    <strong>All Cost:</strong><br>
                    {{ $car->total_cost }}<br>
                    <strong>Amount due:</strong><br>
                    @if($car->latestCredit)
                        <span style="color: red ">{{ round($car->latestCredit->credit_amount+$creditService->totalInterestFromLastCalc($car->id))}}</span>
                    @else
                        <span style="color:red">${{round( $car->amount_due) }}</span>
                    @endif
                </td>

                <td class="text-center">
                    <a href="{{ route('car.edit', $car->id) }}">
                        <button type="button" class="btn green_btn btn-sm">
                            Edit
                        </button>
                    </a>
                    @if(!$car->latestCredit && $car->amount_due > 0)
                        <button type="button" class="btn btn-primary  btn-sm"
                                data-toggle="modal"
                                data-target="{{$car->latestCredit? '' : '#creditmodal'.$index}}">
                            Give Credit
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
                                                        <input type="hidden" name="amount"
                                                               value="{{ $car->amount_due}}">
                                                        <input type="hidden" name="car_id" value="{{ $car->id}}">
                                                        <input type="hidden" name="customer_id"
                                                               value="{{ $car->customer->id}}">
                                                        <input type="text" disabled
                                                               value="{{ $car->amount_due}}" class="form-control">
                                                    </div>

                                                    <div class="col-md-4 form-group">
                                                        <label class="control-label">Monthly %</label>
                                                        <input required name="percent" type="number" min="1"
                                                               placeholder=""
                                                               class="form-control">
                                                    </div>

                                                    <div class="col-md-4 form-group">
                                                        <label class="control-label">Issue Date</label>
                                                        <input min="{{ $car->created_at->format('Y-m-d') }}" required
                                                               name="issue_date" type="date" placeholder="Invoice Date"
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
                    @endif

                    {{--Credit Info Button--}}
                    @if($car->latestCredit)
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#creditinfomodal{{$index}}">
                            Credit Info
                        </button>
                    @endif

                    {{--CreditInfo modal--}}
                    <div class="modal fade " id="creditinfomodal{{$index}}" tabindex="-1" role="dialog"
                         aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 class="modal-title">Issue Date :
                                        {{$car->firstCredit?->issue_or_payment_date->format('d-m-Y')}} :
                                        {{ $car->firstCredit?->credit_amount }} $
                                    </h5>

                                    {{--                                    <p class="mb-0">{{$car->credit->first()?->issue_or_payment_date->format('d-m-Y')}}</p>--}}
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-0 "
                                     style="overflow-x: auto; width: 100%;">
                                    <table style="margin: auto">
                                        <thead>
                                        <tr>
                                            <th class="p-1 text-center" style="min-width: 100px!important;">Date</th>
                                            <th class="p-1 text-center" style="width: 100px">Begin Amount due</th>
                                            <th class="p-1 text-center">Added Amount</th>
                                            <th class="p-1 text-center" style="width: 80px">Credit Days</th>
                                            <th class="p-1 text-center" style="width: 100px">Credit Fee</th>
                                            <th class="p-1 text-center" style="width: 100px">Total Amount</th>
                                            <th class="p-1 text-center" style="width: 60px">Paid Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($car->credit as $index2=> $credit)
                                            <tr>
                                                @if($credit->added_amount!==null)
                                                    <td class="p-1 text-center">{{$credit->issue_or_payment_date->format('d-m-Y')}}</td>
                                                @elseif($index2-1>=0)
                                                    <td style="min-width: 100px"
                                                        class="p-1 text-center">{{$car->credit[$index2]->issue_or_payment_date->format('d-m-Y')}}
                                                    </td>
                                                @endif
                                                @if($index2-1>=0)
                                                    <td class="p-1 text-center">{{round($car->credit[$index2-1]->credit_amount)}}</td>
                                                @endif
                                                <td class="p-1 text-center" style="width:min-content!important">
                                                    {{ $credit->comment? $credit->comment .'-': ''}}  {{ $credit->added_amount? $credit->added_amount . ' $ ':'' }}
                                                </td>
                                                <td class="p-1 text-center">{{$credit->credit_days}}</td>
                                                <td class="p-1 text-center">{{$credit->accrued_percent==null? '': round($credit->accrued_percent)}}</td>
                                                @if($index2-1>=0)
                                                    <td class="p-1 text-center">{{round($car->credit[$index2-1]->credit_amount+$credit->accrued_percent+$credit->added_amount)}}</td>
                                                @endif
                                                <td class="p-1 text-center"
                                                    style="width: 60px!important">{{$credit->paid_amount}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(isset($credit))
                                            <tr style="background: #f2f2f2">
                                                <td class="p-1  text-center">{{Carbon::now()->format('d-m-Y')}}</td>
                                                <td class="p-1  text-center">{{round($credit->credit_amount+round($creditService->totalInterestFromLastCalc($car->id)))}}</td>
                                                <td></td>
                                                <td class="p-1  text-center">{{$creditService->totalDaysFromLastCalcDate($car->id) }}</td>
                                                <td class="p-1  text-center">{{round($creditService->totalInterestFromLastCalc($car->id,)) }}</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    @if(isset($credit))
                                        <div class="d-flex justify-content-center gap-3 mt-3">
                                            <p>Amount Due Till
                                                Today: {{round($credit->credit_amount+round($creditService->totalInterestFromLastCalc($car->id)))}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Delete Button--}}
                    @can('CarUpdate')
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteCarModal{{$index}}" data-car-id="{{ $car->id }}">
                            <i class="fa fa-trash-o"></i>
                        </button>
                        <div class="modal fade" id="deleteCarModal{{$index}}" tabindex="-1" role="dialog"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-primary">
                                        <h3><i class="fa fa-user m-r-5"></i> Delete Car</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to
                                            {!!  request()->has('archive') ? '<span class="text-danger">PERMANENTLY</span>' : ''!!}
                                            delete this Car?</p>
                                        <p>{{$car->vin}}</p>
                                        <form action="{{ route('car.destroy', $car->id) }}" method="POST">
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
                    @endcan
                    {{--credit recalculation--}}
                    @role('Developer')
                    @if($car->latestCredit)
                        <form style="all: unset" action="{{route('credit.total.recalc')}}" method="post">
                            @csrf
                            <input type="hidden" name="car_id" value="{{$car->id}}">
                            <button class="btn btn-warning btn-sm">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </form>
                    @endif
                    @endrole
                    @if(request()->has('archive'))
                        <form style="all: unset" action="{{route('car.trashed.restore', ['id' =>$car->id])}}">
                            <input type="hidden" name="id" value="">
                            <button class="btn btn-success btn-sm">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </form>
                    @endif
                    <br>
                    <br>
                    <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }}
                    <br>
                    <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
