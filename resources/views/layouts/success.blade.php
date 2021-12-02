@if ($message = Session::get('success'))
    @php
        $message = ($message == 1) ? '' : $message;
    @endphp

    <script>
        Swal.fire({
            title: 'Success!',
            type: 'success',
            text: '<?=@$message?>',
            showCloseButton: true,
            showConfirmButton: false,
            timer: 2500,
        })
    </script>
@endif