<button type="button"
        class=" btn {{ $container->is_email_sent == 1 ? 'btn-warning' :  ($owner === false ? 'btn-danger' : 'btn-primary') }} btn-sm">
    @if($owner)
    {{ $container->is_email_sent == 1 ? 'Email sent ' . $container->email_sent_date : 'Send email' }}
    @else
        Check Owner Names
    @endif
</button>
