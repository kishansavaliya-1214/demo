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
        $.validator.addMethod("strongPassword", function (value, element) {
            return this.optional(element) ||
                (/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(value));
        }, "Password must be 8-20 characters long and contain at least one uppercase, lowercase, digit, and special character");
        $.validator.addMethod("filesize", function (value, element, param) {
            return this.optional(element) || (element.files[0] && element.files[0].size <= param * 1024);
        }, "File size must be less than {0} KB");
        $.validator.addMethod("genderSpecific", function (value, element) {
            return this.optional(element) || /^(male|female)$/i.test(value);
        }, "Please select either 'male' or 'female'.");

    });
</script>
