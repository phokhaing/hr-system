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
     * Get information staff that want to resign
     */
    $("#staff_id").on('change', function (e) {
        e.preventDefault();
        let input = $("#staff_id").val();

        /**
         * Check length of input.
         *
         * ID card at least 4 characters
         */
        if (input.length >= 4) {
           $.ajax({
               url: APP_URL + "/staff-resign/find",
               dataType: "JSON",
               method: "POST",
               data: {
                    emp_id:input
               },
               success:function (data) {
                   //IF get data successfully
                   if (data.flag == 1) {
                       //IF have data
                       if (data.staff != null) {
                           $("#full_name_kh").val(data.staff.full_name_kh);
                           $("#full_name_en").val(data.staff.full_name_en);
                           var g = "";
                           if (data.staff.gender == 1) {
                               g = "Female";
                           } else {
                               g = "Male";
                           }
                           let obj = data.staff;
                           $("#gender").val(g);
                           $("#company").val(obj.company_name);
                           $("#branch").val(obj.branch_name);
                           $("#department").val(obj.department_name);
                           $("#position").val(obj.position_name);
                           $("#employment_date").val(obj.employment_date);
                           $("#staff_token").val(obj.staff_personal_info_id);
                       }
                   } else if (data.flag == 204) {

                       $("#full_name_kh").val('');
                       $("#full_name_en").val('');
                       $("#gender").val('');
                       $("#company").val('');
                       $("#branch").val('');
                       $("#department").val('');
                       $("#position").val('');
                       $("#employment_date").val('');
                       $("#staff_token").val('');

                       Swal.fire(
                           'Not Found',
                           'The ID not found. Please review again !',
                           'warning',
                           'false'
                       );
                   } else {
                       Swal.fire(
                           'Opp!',
                           'This user already resign! Please ID again.',
                           'warning',
                           'false'
                       );
                   }
                   
               },
               fail:function (err) {
                   console.log(err.message);
               }
           });
        }
    });

    /**
     * When user fill on staff replace id
     */
    $("#staff_id_replace_1").on('change', function (e) {
        e.preventDefault();
        let input = $("#staff_id_replace_1").val();

        //IF get data successfully
        if (input.length >= 4) {
            $.ajax({
                url: APP_URL + "/staff-resign/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id:input
                },
                success:function (data) {
                    if (data.flag == 1) {
                        if (data.staff != null) {
                            $("#staff_replace_name_1").val(data.staff.last_name_kh+" "+data.staff.first_name_kh);
                        }

                    } else if (data.flag == 204) {
                        $("#staff_id_replace_1").val('');
                        Swal.fire(
                            'Not Found',
                            'The ID not found. Please review again !',
                            'warning',
                            'false'
                        );

                    } else {

                        Swal.fire(
                            'Opp!',
                            'This user already resign! Please ID again.',
                            'warning',
                            'false'
                        );
                    }
                },
                fail:function (err) {
                    console.log(err.message);
                }
            });
        }
    });


    $("#staff_id_replace_2").on('change', function (e) {
        e.preventDefault();
        let input = $("#staff_id_replace_2").val();

        //IF get data successfully
        if (input.length >= 4) {
            $.ajax({
                url: APP_URL + "/staff-resign/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id:input
                },
                success:function (data) {
                    if (data.flag == 1) {
                        if (data.staff != null) {
                            $("#staff_replace_name_2").val(data.staff.last_name_kh+" "+data.staff.first_name_kh);
                        }

                    } else if (data.flag == 204) {
                        $("#staff_id_replace_2").val('');
                        Swal.fire(
                            'Not Found',
                            'The ID not found. Please review again !',
                            'warning',
                            'false'
                        );

                    } else {

                        Swal.fire(
                            'Opp!',
                            'This user already resign! Please ID again.',
                            'warning',
                            'false'
                        );
                    }
                },
                fail:function (err) {
                    console.log(err.message);
                }
            });
        }
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

    $("#btnSave").on('click', function (e) {
        e.preventDefault();

        $("#formResign").submit();
        $("#page-loading").fadeIn();
    });

    $("#btnDiscard").on('click', function (e) {
        e.preventDefault();

        $("#formResign").trigger("reset");
    })

});