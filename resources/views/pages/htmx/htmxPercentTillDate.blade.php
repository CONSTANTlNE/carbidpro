

<script>

     totalPercent = parseFloat('{{ $total_percent }}');
     totalDue = parseFloat('{{ $total_due }}');

    document.getElementById('total_percent').value = totalPercent.toFixed(2);
    document.getElementById('totaldue').value = totalDue.toFixed(2);
</script>