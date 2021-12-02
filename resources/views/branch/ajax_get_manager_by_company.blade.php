<script>
    jQuery(document).ready(function(){
        var selector = "{{ isset($selector) ? $selector : '#company_id'}}";
        var url = "{{ isset($url)? $url : '/branch/get_branch_by_company' }}";

        jQuery(selector).on('change', function(e){
            var param =  $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: url,
                method: 'post',
                data: {
                    'id': param
                },
                success: function(result){
                    $('#p_manager').html(result);
                    console.log(result);
                }
            });
        });
    });
</script>
