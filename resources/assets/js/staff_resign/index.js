$(document).ready(function () {
    /**
     * Set up token
     * ===============================
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Button reject resign request
     */
    $(".btn-reject-resign").on('click', function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to reject this request ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject !'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    url: APP_URL + '/staff-resign/'+ id +'/reject',
                    method: "GET",
                    dataType:"JSON",
                    data: {

                    },
                    success:function (data) {
                        if (data.flag === 1) {
                            swal.fire({
                                title: 'Successful!',
                                text: 'Your reject successfully.',
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1800
                            }).then(window.location.reload());
                        }
                    },
                    fail:function (err) {
                        console.log(err);
                    }
                });
            }
        });

    });

    $(".resign_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-M-yy',
        yearRange: "-130:+0",
        // defaultDate: '-1yr',
        maxDate: '+0d'
    });
    $(".approve_date").each(function () {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            yearRange: "-70:+70",
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                $(".last_day").datepicker("option", "minDate", dt);
            }
        });
    });
    $(".last_day").each(function () {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            yearRange: "-70:+70"
        });
    });

    // Event modal show
    $('#EditResign').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var staff_id = button.data('staff_id'); // Extract info from data-* attributes
        var approved_date = button.data('approved-date'); // Extract info from data-* attributes
        var modal = $(this);

        modal.find('.modal-body #approve_date').val('');
        modal.find('.modal-body #last_day').val('');
        modal.find('.modal-body #staff_token').val(staff_id);

        if (approved_date !== '') {
            modal.find('.modal-body #approve_date').val(approved_date);
        }
    });

    // When click save button
    $("#modal-btn-save").on('click', function () {
       $("#EditResign").modal('hide');
       $("#page-loading").fadeIn();
    });

    // When click search button
    $("#filter-resign").on('submit', function () {
        $("#page-loading").fadeIn();
    })

});