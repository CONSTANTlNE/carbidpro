
<div class="modal-body">
    <div style="display: flex" class="flex justify-content-center mb-2">
        <div class="form-group w-100 text-center">
            <label class="text-center">Title</label>
            <input name="title" type="text" class="form-control w-100 text-center"
                   value="{{$announcement->title}}"
                   required="">
        </div>
    </div>
    <div style="display: flex" class="flex justify-content-center mb-2">
        <div class="form-group text-center">
            <label class="text-center">Date</label>
            <input name="date" type="date" class="form-control w-100"
                   value="{{$announcement->date}}"
                   required="">
        </div>
    </div>
    <p style="font-weight: bold" class="text-center ">Content</p>
    <textarea style="display: none" name="announcement"
              id="quill_content{{$id}}">{{ $announcement->content }}</textarea>
    <!-- Quill Editor -->
    <div id="editor{{$id}}"></div>
</div>


<script>
    // Initialize Quill editor
     quill{{$id}} = new Quill('#editor{{$id}}', {
         modules: {
             toolbar: [
                 [{header: [1, 2, false]}],
                 [{size: ['small', 'large', 'huge']}],
                 ['bold', 'italic', 'underline', 'strike'],
                 ['blockquote'],
                 ['link', 'image', 'video'],
                 [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                 [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                 [{ 'align': [] }],
             ],
         },
         placeholder: 'Compose an epic...',
         theme: 'snow', // or 'bubble'
     });

    // Get the textarea
     textarea{{$id}} = document.getElementById('quill_content{{$id}}');

    // Set initial content of the Quill editor from the textarea value
    quill{{$id}}.root.innerHTML = textarea{{$id}}.value;

    // Update textarea when Quill content changes
    quill{{$id}}.on('text-change', () => {
        textarea{{$id}}.value = quill{{$id}}.root.innerHTML;
    });

    // Optional: Update Quill content if textarea changes (not typical for editing UI)
    textarea{{$id}}.addEventListener('input', () => {
        quill{{$id}}.root.innerHTML = textarea{{$id}}.value;
    });

    qlhidden=document.querySelectorAll('.ql-hidden')

    qlhidden.forEach(element => {
        element.style.display='none'
    });

</script>
