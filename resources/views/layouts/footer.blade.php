<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right", // Customize position as needed
        "timeOut": "5000" // Message duration
    }
    @if (session('error'))
        toastr.error("{{ session('error') }}")
    @endif
    @if (session('success'))
        toastr.success("{{ session('success') }}")
    @endif
</script>
