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

    const alert_not_found = () => {
        Swal.fire(
            'Not Found',
            'The ID not found. Please review again !',
            'warning',
            'false'
        );
    };

    const alert_duplicate = () => {
        Swal.fire(
            'Opp!',
            'This user already movement! Please ID again.',
            'warning',
            'false'
        );
    };

    const push_staff_info = (obj) => {
        $("#full_name_kh").val(obj.last_name_kh+" "+obj.first_name_kh);
        $("#full_name_en").val(obj.last_name_en+" "+obj.first_name_en);
        var g = "";
        if (obj.gender === 1) {
            g = "Female";
        } else {
            g = "Male";
        }
        var position = obj.profile.position;
        $("#gender").val(g);
        $("#old_company").val(obj.profile.company.short_name ? obj.profile.company.short_name : '');
        $("#old_branch").val(obj.profile.branch.name_kh ? obj.profile.branch.name_kh : '');
        $("#old_department").val(obj.profile.department ? obj.profile.department.name_kh : '');
        $("#old_position").val(position.name_kh);
        $("#staff_token").val(obj.profile.staff_personal_info_id);
    };

    /**
     * Get information staff that want to resign
     * =========================================
     */
    $("#staff_id").on('change', function (e) {
        e.preventDefault();
        let input = $("#staff_id").val();

        // ID card at least 4 characters
        if (input.length >= 4) {
            $.ajax({
                url: "/staff-movement/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id:input
                },
                success:function (data) {
                    // console.log(data);
                    if (data.flag === 1) { //IF get data successfully

                        if (data.staff != null) { //IF have data
                            push_staff_info(data.staff);

                        } else {
                            alert_not_found();
                        }

                    } else if (data.flag === 204) {
                        alert_not_found();

                    } else {
                        $("#staff_id").val('');
                        alert_duplicate();
                    }
                },
                error:function (err) {
                    console.log(err.message)
                }
            });
        }
    });

    /**
     * Get staff transfer to name
     * ===========================
     */
    $("#transfer_to_id").on('change', function (e) {
        e.preventDefault();
        let input = $("#transfer_to_id").val();

        // ID card at least 4 characters
        if (input.length >= 4) {
            $.ajax({
                url: "/staff-movement/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id:input
                },
                success:function (data) {
                    if (data.flag === 1) { //IF get data successfully

                        if (data.staff != null) { //IF have data
                            $("#transfer_to_name").val(data.staff.last_name_kh+" "+data.staff.first_name_kh);

                        } else {
                            $("#transfer_to_id").val('');
                            alert_not_found();
                        }

                    } else if (data.flag === 204) {
                        $("#transfer_to_id").val('');
                        $("#transfer_to_name").val('');

                        alert_not_found();

                    } else {
                        alert_duplicate();
                    }
                },
                fail:function (err) {
                    console.log(err.message)
                }
            });
        }
    });

    /**
     * Get staff get work from name
     * ===========================
     */
    $("#get_work_form_id").on('change', function (e) {
        e.preventDefault();
        let input = $("#get_work_form_id").val();

        // ID card at least 4 characters
        if (input.length >= 4) {
            $.ajax({
                url: "/staff-movement/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id:input
                },
                success:function (data) {
                    if (data.flag === 1) { //IF get data successfully

                        if (data.staff != null) { //IF have data
                            $("#get_work_form_name").val(data.staff.last_name_kh+" "+data.staff.first_name_kh);

                        } else {
                            alert_not_found();
                        }

                    } else if (data.flag === 204) {
                        $("#get_work_form_id").val('');
                        $("#get_work_form_name").val('');

                        alert_not_found();

                    } else {
                        alert_duplicate();
                    }
                },
                fail:function (err) {
                    console.log(err.message)
                }
            });
        }
    });

    $(".effective_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-M-yy',
        yearRange: "-130:+2",
        // defaultDate: '-1yr',
        maxDate: '+1yr'
    });

    /**
     * Action click button save staff movement
     */
    $("#btnSave").on('click', function (e) {
        e.preventDefault();

        $("#formMovement").submit();
        $("#page-loading").fadeIn();
    });

    /**
     * Discard input staff movement
     */
    $("#btnDiscard").on('click', function (e) {
        e.preventDefault();

        $("#formMovement").trigger("reset");
    });

    /**
     * Update staff movement
     */
    $("#btnUpdate").on('click', function (e) {
        e.preventDefault();

        $('#UpdateMovement').submit();
        $("#page-loading").fadeIn();
    });

});