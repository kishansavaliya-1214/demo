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
    $(function () {
        // Add a custom validation method named "strongPassword"
        $.validator.addMethod("strongPassword", function (value, element) {
            // optional() checks if the field is empty, returning true if not required
            return this.optional(element) ||
                (/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(value));
        }, "Password must be 8-20 characters long and contain at least one uppercase, lowercase, digit, and special character");
    });
</script>
