@extends('layouts.app')



@section('allsms')
    @include('partials.header')
    @include('partials.aside')
    @push('css')
        <style>
            table, th, td {
                border: 1px solid black;
                padding: 10px;
            }

            .pagination {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 10px;
            }

            .pagination button {
                margin: 0 5px;
                padding: 5px 10px;
                cursor: pointer;
                border: 1px solid #ccc;
                background-color: #f9f9f9;
            }

            .pagination button:hover {
                background-color: #ddd;
            }

            .pagination #pageInfo {
                margin: 0 10px;
            }
        </style>
    @endpush
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
                                        <td><input class="form-control w-100" type="text" name="numbers[]" value=""
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
        {{--Send sms To all--}}
        <section class="content">
            <div class="row justify-content-center">

                <div class="col-lg-8">
                    <form action="{{route('sms.send.selected')}}" method="post">
                        @csrf
                        <div class="card-body d-flex justify-content-center ">
                            <div class="d-flex flex-column align-middle ">
                                <div class="d-flex justify-content-around">
                                    <input style="max-width: 250px"
                                           type="text"
                                           id="searchInput"
                                           placeholder="Search for names.."
                                           onkeyup="searchTable()"
                                    />
                                    <div class="d-flex justify-content-between ">
                                        <label class="btn btn-primary" style="cursor: pointer" for="selectall">Select All
                                            <input style="width: 15px;height: 15px" type="checkbox" id="selectall">
                                        </label>

                                    </div>

                                </div>

                                <table id="myTable" class="mt-2 mb-3">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Company Name</th>
                                        <th>Number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customers as $index => $customer)
                                        <tr>
                                            <td>{{$customer->contact_name}}</td>
                                            <td>{{$customer->company_name}}</td>
                                            <td class="d-flex justify-content-center align-middle">
                                                <label style="cursor: pointer" class="mr-2"
                                                       for="number{{$index}}"> {{$customer->phone}}</label>
                                                <input id="number{{$index}}"
                                                       style="height: 20px;width: 20px;cursor: pointer"
                                                       type="checkbox"
                                                       name="phone[]"
                                                       class="numbers"
                                                       value="{{$customer->phone}}"
                                                        @checked(is_array(old('phone')) && in_array($customer->phone, old('phone')))>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!-- Pagination Controls -->
                                <div class="pagination mb-3">
                                    <button type="button" id="prevBtn">Previous</button>
                                    <span id="pageInfo"></span>
                                    <button type="button" id="nextBtn">Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea name="message" id="" class="w-100" rows="10">{{old('message')}}</textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-add"><i class="fa fa-check"></i> send
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
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
                                    <label>Send SMS to Single Bumber</label>
                                    <input style="max-width: 150px;margin:auto;text-align: center" type="text" class="form-control"
                                           name="number" placeholder="Enter Phone">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectall=document.getElementById('selectall');
            const numbers=document.querySelectorAll('.numbers');
            selectall.addEventListener('click', function () {
                numbers.forEach(function (number) {
                    number.checked = selectall.checked;
                });
            });




            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = Array.from(tbody.getElementsByTagName('tr'));
            const rowsPerPage = 10; // Number of rows to display per page
            let currentPage = 1;
            let filteredRows = rows; // Initially, all rows are visible

            // Function to display rows for the current page
            function displayRows() {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                // Hide all rows initially
                rows.forEach(row => row.style.display = 'none');

                // Show only the filtered rows for the current page
                filteredRows.slice(start, end).forEach(row => row.style.display = '');

                // Update page info
                document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${Math.ceil(filteredRows.length / rowsPerPage)}`;

                // Enable/disable pagination buttons based on current page
                document.getElementById('prevBtn').disabled = currentPage === 1;
                document.getElementById('nextBtn').disabled = currentPage === Math.ceil(filteredRows.length / rowsPerPage);
            }

            // Event listener for "Previous" button
            document.getElementById('prevBtn').addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    displayRows();
                }
            });

            // Event listener for "Next" button
            document.getElementById('nextBtn').addEventListener('click', function () {
                if (currentPage < Math.ceil(filteredRows.length / rowsPerPage)) {
                    currentPage++;
                    displayRows();
                }
            });

            // Search function
            function searchTable() {
                const input = document.getElementById('searchInput');
                const filter = input.value.toLowerCase();

                // Filter rows based on search input
                filteredRows = rows.filter(row => {
                    const cells = row.getElementsByTagName('td');
                    for (let cell of cells) {
                        if (cell.textContent.toLowerCase().includes(filter)) {
                            return true; // Include row if any cell matches the filter
                        }
                    }
                    return false; // Exclude row if no cell matches
                });

                // Reset to the first page after filtering
                currentPage = 1;
                displayRows();
            }

            // Attach search function to input
            document.getElementById('searchInput').addEventListener('input', searchTable);

            // Initial display
            displayRows();
        });
    </script>
    <!-- Modal js -->
    <script src="{{asset('assets/plugins/modals/classie.js')}}"></script>
    <script src="{{asset('assets/plugins/modals/modalEffects.js')}}"></script>
    <!-- End Page Lavel Plugins










@endsection