
<div class="modal-body">

    <p style="font-weight: bold" class="text-center ">Content</p>
    <textarea style="display: none" name="insurancetext"
              id="quill_content{{$id}}">{{ $insurance->text }}</textarea>
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
