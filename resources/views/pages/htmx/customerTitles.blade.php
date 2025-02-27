<form class="form-horizontal"
      action="{{route('customers.delete')}}"
      method="post">
    @csrf
    <input type="hidden" name="id"
           value="{{$customer->id}}">
    <fieldset>
        <div class="col-md-12 form-group user-form-group">

            <select name="" id="" class="select-beast">
                @foreach($titles as $title)
                    <option value="{{$title->id}}">{{$title->name}}</option>
                @endforeach
            </select>
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