@if ($errors->any())
    <?php $html = '<ul class="text-left text-danger">'; ?>
    @foreach ($errors->all() as $error)
        <?php $html .= '<li>'. $error .'</li>'; ?>
    @endforeach
    <?php $html .= '</ul>'; ?>

    <script>
        var htmlError = '<?= $html ?>';
        Swal.fire({
            title: 'Warning!',
            html: htmlError,
            type: 'warning',
            showCloseButton: true,
            showConfirmButton: false,
        })
    </script>
@endif