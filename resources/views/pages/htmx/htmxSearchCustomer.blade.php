@if($customers->isEmpty())
    <div class="d-flex justify-content-center">
        No Records
    </div>
@else

    <table style="width: 100%!important;">
        <thead>
        <tr style="text-align: center">
            <th>Customer Name</th>
            <th>Company Name</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
            <tr
                   {{--Index is set when search is used for update modal--}}
                    @if(isset($index))
                        onclick="document.getElementById('customerName'+{{$index}}).value = '{{$customer->contact_name}}';
                        document.getElementById('customerID'+{{$index}}).value = {{$customer->id}};
                        document.getElementById('deposit'+{{$index}}).value='{{$customer->deposit}}';
                        document.getElementById('closeSearch'+{{$index}}).click();
                        "
                    @else
                        onclick="document.getElementById('customerName').value = '{{$customer->contact_name}}';
                        document.getElementById('customerID').value = {{$customer->id}};
                        document.getElementById('deposit').value='{{$customer->deposit}}';
                        document.getElementById('closeSearch').click();
                          "
                    @endif


        "
                    style="text-align: center">
                <td style="cursor: pointer" class="mr-1 border">{{$customer->contact_name}}</td>
                <td style="cursor: pointer" class="mr-1 border">{{$customer->company_name}}</td>
                <td style="cursor: pointer" class="mr-1 border">{{$customer->email}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif