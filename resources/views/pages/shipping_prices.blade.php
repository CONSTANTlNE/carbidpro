@extends('layouts.app')

{{--@dd($shipping_prices)--}}
@push('css')

@endpush
@section('shipping-prices')
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
                                <h2>Shipping Price Count {{$count}}</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="btn-group flex">
                                {{-- Location Create Modal --}}
                                <div>
                                    <button type="button" class="btn green_btn custom_grreen2 ml-2 mb-3 "
                                            data-toggle="modal"
                                            data-target="#mymodals">
                                        Add New Price
                                    </button>
                                </div>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    {{--Hidden Location Dropdow--}}
                                    <div id="locationDropdown" class="dropdown"
                                         style="display: none; position: absolute;top:150px ;left: 49.5%; transform: translateX(-49.5%);z-index: 100000;   width: 498px; border: 1px solid #ccc; background: #fff; max-height: 500px; overflow-y: auto;">
                                        @foreach($locations as $location)
                                            <p class="location-item" style="display: none">{{$location->name}}</p>
                                        @endforeach
                                    </div>
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Price</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('shipping-prices.store')}}" method="post">
                                                @csrf
                                                <div class="modal-body" style="overflow: visible">
                                                    <div style="display: flex;gap:20px">
                                                        <input type="text" id="locationSearch" class="form-control"
                                                               placeholder="Search Location"
                                                               style="max-width: 150px; margin-bottom: 10px;">

                                                        <select id="locationSelect" required name="location_id"
                                                                style="max-width: 150px"
                                                                class="form-control">
                                                            <option value="">From Location</option>
                                                            @foreach($locations as $location)
                                                                <option class="options"
                                                                        value="{{$location->id}}">{{$location->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div style="display: flex;gap:20px">
                                                        <select required name="port_id" style="width: 150px"
                                                                class=" form-control">
                                                            <option value="">To Port</option>
                                                            @foreach($ports as $port)
                                                                <option value="{{$port->id}}">{{$port->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <input style="width: 150px; " required type="number"
                                                               name="price"
                                                               placeholder="Price" class="form-control">
                                                    </div>

                                                    <div style="display: flex;flex-wrap: wrap; gap: 10px;margin-top: 10px">
                                                        @foreach($auctions as $auction)
                                                            <div style="margin: 10px; width: 80px;">
                                                                <label class="text-center">{{$auction->name}}</label>
                                                                <input class="form-control" type="checkbox"
                                                                       name="auction_id[]" value="{{$auction->id}}">
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
                                {{--End Location Create Modal --}}

                                {{-- Per Page filter--}}
                                <form action="{{route('shipping-prices.index')}}">
                                    <select style="width: 70px" class="ml-3 form-control" name="perpage" id=""
                                            onchange="this.form.submit()">
                                        <option value="10" {{request('perpage') == 10 ? 'selected' : ''}}>10</option>
                                        <option value="25" {{request('perpage') == 25 ? 'selected' : ''}}>25</option>
                                        <option value="50" {{request('perpage') == 50 ? 'selected' : ''}}>50</option>
                                        <option value="100" {{request('perpage') == 100 ? 'selected' : ''}}>100</option>
                                        <option value="500" {{request('perpage') == 500 ? 'selected' : ''}}>500</option>
                                    </select>
                                </form>
                                {{-- Auction filter--}}
                                <form action="{{route('shipping-prices.index')}}">
                                    <input name="port" type="hidden" value="{{request()->query('port')}}">
                                    <input type="hidden" name="maxprice" value="{{request()->query('maxprice')}}">
                                    <input type="hidden" name="minprice" value="{{request()->query('minprice')}}">
                                    <select style="width: 120px" class="ml-3 form-control" name="auction" id=""
                                            onchange="this.form.submit()">
                                        <option value="all">All Auctions</option>
                                        @foreach($auctions as $auction)
                                            <option @selected(request()->query('auction') == $auction->id) value="{{$auction->id}}">{{$auction->name}}</option>
                                        @endforeach
                                    </select>
                                </form>
                                {{-- Filter By Ports --}}
                                <form action="{{route('shipping-prices.index')}}">
                                    <input name="auction" type="hidden" value="{{request()->query('auction')}}">
                                    <input type="hidden" name="maxprice" value="{{request()->query('maxprice')}}">
                                    <input type="hidden" name="minprice" value="{{request()->query('minprice')}}">
                                    <select style="width: 150px" class="ml-3 form-control" name="port" id=""
                                            onchange="this.form.submit()">
                                        <option value="all">All Ports</option>
                                        @foreach($ports as $port)
                                            <option @selected(request()->query('port') == $port->id) value="{{$port->id}}">{{$port->name}}</option>
                                        @endforeach
                                    </select>
                                </form>
                                {{-- Filter By Price --}}
                                <form style="display: flex;flex-direction :column;align-items: center"
                                      action="{{route('shipping-prices.index')}}">
                                    <div style="display: flex;gap: 10px" class="ml-3">
                                        <input name="auction" type="hidden" value="{{request()->query('auction')}}">
                                        <input name="port" type="hidden" value="{{request()->query('port')}}">
                                        <input value="{{request()->query('minprice')}}"
                                               type="text" name="minprice" class="form-control"
                                               placeholder="Min Price">
                                        <p>-</p>
                                        <input value="{{request()->query('maxprice')}}"
                                               type="text" name="maxprice" class="form-control"
                                               placeholder="Max Price">
                                    </div>
                                    <button class="btn green_btn custom_grreen2 mb-3 mt-2" data-toggle="modal">
                                        Filter Price
                                    </button>
                                </form>
                                {{--Clear All Filters --}}
                                <form action="{{route('shipping-prices.index')}}">
                                    <button type="submit"
                                            class="btn green_btn custom_grreen2 ml-2 mb-3 ">Clear All Filters
                                    </button>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>ID</th>
                                        <th>Location From</th>
                                        <th>To Port</th>
                                        <th>Auction</th>
                                        <th>price</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($shipping_prices as $index=> $shippingprice)
                                        {{--@dd($shippingprice->auction_id)--}}
                                        <tr class="text-center">
                                            <td> {{$shippingprice->id}}</td>
                                            <td>{{$shippingprice->location->name}}</td>
                                            <td>{{$shippingprice->port->name}}</td>
                                            <td>
                                                @foreach($shippingprice->auction_id as $index2=> $auctionName)
                                                    @foreach($auctions as $auction)
                                                        @if($auctionName==$auction->id)
                                                            {{$auction->name}}
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td>{{$shippingprice->price}}</td>
                                            <td>
                                                {{--Edit Modal (location search and insert via HTMX)--}}
                                                <button type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    {{--Target fot HTMX when searching locations by name--}}
                                                    <div class="dropdown locationDropdown" id="target{{$index}}">

                                                    </div>
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3> Edit Location</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <form action="{{route('shipping-prices.update')}}"
                                                                  method="post">
                                                                @csrf
                                                                <input type="hidden" value="{{$shippingprice->id}}"
                                                                       name="id">
                                                                <div class="modal-body" style="overflow: visible">
                                                                    <div style="display: flex;gap:20px">

                                                                        <input type="text"
                                                                               hx-vals='{"index": {{$index}}}'
                                                                               hx-trigger="input"
                                                                               hx-get="{{ route('htmx.locations')}}"
                                                                               hx-target="#target{{ $index }}"
                                                                               name="search"
                                                                               id="locationSearch{{ $index }}"
                                                                               class="form-control locationSearch"
                                                                               placeholder="Search Location"
                                                                               style="max-width: 150px; margin-bottom: 10px;">

                                                                        <select required name="location_id"
                                                                                style="max-width: 150px"
                                                                                class="form-control locationSelect{{$index}}">
                                                                            <option value="">From Location</option>
                                                                            @foreach($locations as $optionsindex=> $location)
                                                                                <option class="options2"
                                                                                        @selected($shippingprice->from_location_id===$location->id) value="{{$location->id}}">{{$location->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div style="display: flex;gap:20px">
                                                                        <select required name="port_id"
                                                                                style="width: 150px"
                                                                                class=" form-control">
                                                                            <option value="">To Port</option>
                                                                            @foreach($ports as $port)
                                                                                <option @selected($shippingprice->to_port_id===$port->id) value="{{$port->id}}">{{$port->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input style="width: 150px; " required
                                                                               type="number" name="price"
                                                                               value="{{$shippingprice->price}}"
                                                                               placeholder="Price" class="form-control">
                                                                    </div>

                                                                    <div style="display: flex;flex-wrap: wrap; gap: 10px;margin-top: 10px">
                                                                        @foreach($auctions as $auction)
                                                                            <div style="margin: 10px; width: 80px;">
                                                                                <label class="text-center">{{$auction->name}}</label>
                                                                                <input class="form-control"
                                                                                       type="checkbox"
                                                                                       @checked(in_array($auction->id, $shippingprice->auction_id))  name="auction_id[]"
                                                                                       value="{{$auction->id}}">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
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
                                                                              action="{{route('shipping-prices.destroy')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$shippingprice->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Auction
                                                                                        : {{$shippingprice->name}}
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
                                @if($shipping_prices->isEmpty())
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
                {{ $shipping_prices->links() }}
            </div>
        </section>
        <!-- /.content -->
    </div>

    <script>

        //  Dynamic search of locations for new shipping price modal
        const locationsearch = document.getElementById('locationSearch');
        const items = document.querySelectorAll('.location-item');
        const options = document.querySelectorAll('.options');

        const locationDropdown = document.getElementById('locationDropdown');

        locationsearch.addEventListener('input', function () {
            var searchQuery = this.value.toLowerCase(); // Get search query
            items.forEach(function (item, index) {
                var optionText = item.textContent.toLowerCase(); // Get text of each option
                if (optionText.includes(searchQuery) || searchQuery === '') {
                    locationDropdown.style.display = 'block';
                    item.style.display = 'block'; // Show the option if it matches the search
                    console.log(item + 'block')
                    item.addEventListener('click', function () {
                        options[index].selected = true;
                        locationDropdown.style.display = 'none';
                        locationsearch.value = item.textContent;
                    })

                } else {
                    item.style.display = 'none'; // Hide the option if it doesn't match
                }
            });
        });


        // Close the dropdown if clicked outside
        document.addEventListener('click', function (event) {
            if (!locationDropdown.contains(event.target) && event.target !== locationDropdown) {
                locationDropdown.style.display = 'none'; // Hide dropdown if clicked outside
            }
        });


    </script>
@endsection