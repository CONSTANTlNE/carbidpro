<div class="modal-body">
    <div style="display: flex" class="flex justify-content-center mb-2">
        <div class="form-group">
            <label class="text-center">Label</label>
            <input name="new_label" type="text" class="form-control" value="{{$setting->label}}" required="">
        </div>
        <div class="form-group">
            <label class="text-center">Key</label>
            <input name="new_key" type="text" class="form-control" value="{{$key}}" required="">
        </div>
    </div>
    <p class="text-center">Text</p>
    <!-- Hidden Textarea -->
    <textarea style="display: none" name="setting_value" id="quill_content{{$key}}">{{ $setting->value }}</textarea>
    <!-- Quill Editor -->
    <div id="editor{{$key}}"></div>
</div>

<script>
    // Initialize Quill editor
     quill{{$key}} = new Quill('#editor{{$key}}', {
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
     textarea{{$key}} = document.getElementById('quill_content{{$key}}');

    // Set initial content of the Quill editor from the textarea value
    quill{{$key}}.root.innerHTML = textarea{{$key}}.value;

    // Update textarea when Quill content changes
    quill{{$key}}.on('text-change', () => {
        textarea{{$key}}.value = quill{{$key}}.root.innerHTML;
    });

    // Optional: Update Quill content if textarea changes (not typical for editing UI)
    textarea{{$key}}.addEventListener('input', () => {
        quill{{$key}}.root.innerHTML = textarea{{$key}}.value;
    });

    qlhidden=document.querySelectorAll('.ql-hidden')

    qlhidden.forEach(element => {
        element.style.display='none'
    });

</script>
