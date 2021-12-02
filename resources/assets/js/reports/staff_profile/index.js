$(document).ready(function () {
    $("#start_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-M-yy',
        yearRange: "-130:+1",
        // defaultDate: '-1yr',
        // maxDate: '+0d',
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
            $("#end_date").datepicker("option", "minDate", dt);
        }
    });

    $("#end_date").each(function () {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            yearRange: "-70:+70",

        });
    });


    /*
    |================================================
    |               Action of button
    |================================================
    |   This block cover all of action that user
    |   click on some button.
    |
    |
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Button view report staff profile
    $("#view_staff_profile").on('click', function (e) {
        e.preventDefault();
        $("#page-loading").fadeIn();

        $.ajax({
            url: APP_URL + "/report/view-staff-profile",
            method: "GET",
            dataType: "JSON",
            data: $('#report_staff_profile').serialize(),
            success:function (data) {
                if (data.flag === 1) {
                    $("#listView").html("");
                    $("#listView").html(data.list);
                }
                $("#page-loading").fadeOut();
            },
            fail:function (err) {
                console.log(err);
            }

        });
    });





});