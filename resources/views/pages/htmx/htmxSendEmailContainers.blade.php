<button type="button"
        class=" btn {{ $container->is_email_sent == 1 ? 'btn-warning' : 'btn-primary' }} btn-sm">
    {{ $container->is_email_sent == 1 ? 'Email sent ' . $container->email_sent_date : 'Send email' }}
</button>
