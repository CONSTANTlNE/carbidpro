@extends('layouts.app')



@section('allsms')
    @include('partials.header')
    @include('partials.aside')

    <div class="content-wrapper">
        <section class="content-header" style="height: 60px">
            <div class="header-icon">
                <i class="fa fa-mobile"></i>
            </div>
            <div class="header-title">
                <h1>SMS</h1>
            </div>
        </section>
        @if($errors->any())
            <div class="d-flex justify-content-center mt-3">
                <div style="padding: 5px!important;"
                     class=" ml-3 alert custom_alerts alert-danger alert-dismissible fade show w-25" role="alert">
                    @foreach($errors->all() as $error)
                        <p>{{$error}}</p>
                    @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        @endif

        {{--Employee Numbers--}}
        <div class="d-flex justify-content-center mt-3">
            <button type="button" class="btn btn-purple" data-toggle="modal" data-target="#bigmode">New Deposit
                Numbers
            </button>
            <div class="modal fade" id="bigmode" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{route('sms.deposit.number.update')}}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Numbers for sending new deposit notification</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <template id="template">
                                    <tr>
                                        <td>
                                            <input class="form-control w-100" type="text" name="name[]" value=""
                                                   placeholder="Employee name">
                                        </td>
                                        <td><input class="form-control w-100" type="text" name="number[]" value=""
                                                   placeholder="Employee number"></td>
                                        <td>
                                            <button class="btn btn-danger" type="button" onclick="removeRow(this)">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <table class="table table-bordered text-center">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Number</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                    @foreach($depositNumbers as $depositNumber)
                                        <tr>
                                            <td>
                                                <input class="form-control w-100" type="text" name="name[]" value="{{$depositNumber->employee}}"
                                                       placeholder="Employee name">
                                            </td>
                                            <td><input class="form-control w-100" type="text" name="numbers[]" value="{{$depositNumber->number}}"
                                                       placeholder="Employee number"></td>
                                            <td>
                                                <button class="btn btn-danger" type="button" onclick="removeRow(this)">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button id="addNewNumber" type="button" class="btn btn-primary"
                                        onclick="addRow()"
                                >Add New
                                </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button  class="btn  btn-success">Save changes</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{--Display INVALID NUMBERS--}}
        <div class="d-flex flex-column align-items-center justify-content-center mt-3">
            @if(Cache::get('invalidPhones'))
                <b>INVALID NUMBERS</b>
                <div class="d-flex flex-wrap justify-content-center ">
                    @foreach(Cache::get('invalidPhones') as $invalidnumber)
                        <p class="m-2 color-red">{{$invalidnumber}}</p>
                    @endforeach
                </div>
                <form action="{{route('sms.invalid.clear')}}">
                    <button type="submit" class="btn btn-danger">Clear Numbers</button>
                </form>
            @endif
        </div>
        {{--Send SMS to All CUSTOMERS--}}
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('sms.send.all')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Message (To All Customers)</label>
                                    <textarea name="message" id="" class="w-100" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-add"><i class="fa fa-check"></i> send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{--Send SMS to particular number--}}
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{route('sms.send.recipient')}}" method="post">
                                @csrf
                                <div class="form-group text-center">
                                    <label>Number</label>
                                    <input style="max-width: 150px;margin:auto" type="text" class="form-control"
                                           name="number">
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                        <label>Message</label>
                                    </div>
                                    <textarea name="message" id="" class="w-100" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-add"><i class="fa fa-check"></i> send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <script>
        const tableBody = document.getElementById('tableBody');
        const template = document.getElementById('template');

        function addRow() {
            const clone = template.content.cloneNode(true);
            tableBody.appendChild(clone);
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
        }
    </script>
    <!-- Modal js -->
    <script src="{{asset('assets/plugins/modals/classie.js')}}"></script>
    <script src="{{asset('assets/plugins/modals/modalEffects.js')}}"></script>
    <!-- End Page Lavel Plugins










@endsection