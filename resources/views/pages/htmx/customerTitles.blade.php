{{--add custom title status to customer--}}
<form class="form-horizontal"
      action="{{route('customer.titles.add')}}"
      method="post">
    @csrf
    <input type="hidden" name="id"
           value="{{$customer->id}}">
    <fieldset>
        <div class="col-md-12 form-group user-form-group">

            <select name="title_id" class="select-beast mb-3" required>
                <option value="">Select Title</option>
                @foreach($titles as $title)
                    <option value="{{$title->id}}">{{$title->name}}</option>
                @endforeach
            </select>
            <label class="text-center">Custom Status for customer</label>

            <div style="display: flex;justify-content: center;gap: 10px;margin-top: 5px">
                <input required type="text" name="title_for_customer" class="form-control" style="max-width: 150px">
                <button type="submit"
                        class="btn btn-add btn-sm">
                    Add
                </button>
            </div>
        </div>
    </fieldset>
</form>



<table class="table table-bordered table-striped table-hover">
    <thead class="back_table_color">
    <tr class="info">
        <th class="text-center">Title</th>
        <th>Custom Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customer->titles as $customtitle)
        <tr>
            <td class="text-center">{{$customtitle->name}}</td>
            <td class="text-center">
                <input
                        hx-trigger="focusout"
                        hx-post="{{route('customer.titles.update')}}"
                        hx-vals='{"id": "{{$customer->id}}","title_id": "{{$customtitle->id}}","_token": "{{ csrf_token() }}" }'
                        name="title_for_customer"
                        class="form-control"
                        type="text"
                        value=" {{$customtitle->pivot->title_for_customer}}"
                        readonly
                        onclick="this.readOnly = false;"
                        onblur="this.readOnly=true"
                >
            </td>
            <td>
                <div style="display: flex;justify-content:center">
                    <form style="width: min-content" action="{{route('customer.titles.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$customer->id}}">
                        <input type="hidden" name="title_id" value="{{$customtitle->id}}">
                        <button style="all:unset;cursor: pointer;" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="#dd0d0d"
                                      d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


<script>

    titleSelects = document.querySelectorAll('.select-beast');

    // document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        titleSelects.forEach((select) => {

            if (select.tomselect) {
                // Destroy the existing Tom Select instance
                select.tomselect.destroy();
                console.log('Tom Select instance destroyed');
            }

            new TomSelect(select, {
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        })

    }, 200)
    // })

    titles = {!! $titles !!};


</script>