
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".btn-delete-movement").on('click', function () {
        swal.fire({
            title: 'Are you sure ?',
            text: 'Do you want to delete ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete !'
        }).then((result) => {
            if (result.value) {
                let id = $(this).attr('move_id');

                $.ajax({
                    url: APP_URL + '/staff-movement/'+ id +'/destroy',
                    method: "GET",
                    dataType: "JSON",
                    data: {

                    },
                    success:function(data) {
                        if (data.flag === 1) {
                            swal.fire({
                                title: 'Successful!',
                                text: 'Staff movement was deleted.',
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1800
                            }).then(window.location.reload());
                        }
                    },
                    fail:function(err) {
                        console.log(err.message);
                    }
                });
            }
        });
    });

    // When click button search
    $("#filter-movement").on('submit', function () {
        $("#page-loading").fadeIn();
    });

});