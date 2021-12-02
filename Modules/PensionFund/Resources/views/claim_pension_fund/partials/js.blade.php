<script>
    $(document).ready(function() {

        var days = function(month,year) {
            return new Date(year, month, 0).getDate();
        };

        $('#staff').select2({
            placeholder: "Staff ID, Name"
        });

        var total_pf_staff = 0;
        var total_pf_company = 0;
        var base_salary = 0;
        var pf_id = 0;
        var staff_personal_id;
        var contract_id;

        $('#staff').change(function(){

            //when change to set value to 0
            $(".data").val("");
            $(".value-data").val(0);
            $(".money").text("0");

            const staff_id = $(this).val();
            console.log(staff_id)

            $.ajax({
                type : 'get',
                url : "{{ route('pensionfund::get.info') }}",
                data : {'id' : staff_id},
                success:function(data){
                    //console.log(data);
                    var obj=JSON.parse(data);

                    $("#first_name_kh").val(obj.staff["first_name_kh"]);
                    $("#last_name_kh").val(obj.staff["last_name_kh"]);
                    $("#first_name_en").val(obj.staff["first_name_en"]);
                    $("#last_name_en").val(obj.staff["last_name_en"]);
                    $("#marital_status").val(obj.marital_status);
                    $("#gender").val(obj.sex);
                    $("#position").val(obj.currentPosition);
                    $("#doe").val(obj.start_date);

                    // console.log(obj.pensionfund["json_data"]["base_salary"]);
                    // console.log(obj.pensionfund["json_data"]["acr_balance_skp"]);
                    // console.log(obj.pensionfund["json_data"]["acr_balance_staff"]);

                    total_pf_staff = parseInt(obj.pensionfund["json_data"]["acr_balance_staff"]) || 0;
                    total_pf_company = parseInt(obj.acr_balance_company) || 0;

                    base_salary = parseInt(obj.pensionfund["json_data"]["base_salary"]) || 0;
                    pf_id = parseInt(obj.pensionfund["id"]) || 0;
                    staff_personal_id = parseInt(obj.staff["id"]);
                    contract_id = parseInt(obj.pensionfund["contract_id"]);

                    $("#pf_company").val(obj.interest_rate + "%");
                    $("#pf_company_interest_rate").val(obj.interest_rate);
                    $("#num_pf_staff").val(total_pf_staff);
                    $("#total_pf_staff").val(total_pf_staff);
                    $("#total_pf_staff_label").text(total_pf_staff);

                    $("#num_pf_company").val(total_pf_company);
                    $("#total_pf_company").val(total_pf_company);
                    $("#total_pf_company_label").text(total_pf_company);

                    $("#pf_id").val(pf_id);
                    $("#staff_personal_id").val(staff_personal_id);
                    $("#contract_id").val(contract_id);

                    checkData();
                    formartnumber();
                }
            });
        });

        checkData();
        //check if have data to enable button submit
        function checkData(){
            let pf_id = parseInt($("#pf_id").val()) || 0;
            let staff_personal_id = parseInt($("#staff_personal_id").val()) || 0;
            let contract_id = parseInt($("#contract_id").val()) || 0;
            console.log("checkData: "+ pf_id + ", " + staff_personal_id + ", "+ contract_id);

            if (pf_id > 0 && staff_personal_id > 0 && contract_id>  0) {
                $("#submit").removeAttr("disabled");
            }
            else {
                $("#submit").attr("disabled", true);
            }
        }

        function calulateMoto(){
            let num_amount_moto = parseInt($("#num_amount_moto").val()) || 0;
            let total_day_moto = parseInt($("#total_day_moto").val()) || 0;
            let total_moto = num_amount_moto * total_day_moto;
            $("#total_moto").val(total_moto);
            $("#total_moto_label").text(total_moto);
            calulateNetPay();
        }

        function calulateGasoline(){
            let num_amount_gasoline = parseInt($("#num_amount_gasoline").val()) || 0;
            let total_day_gasoline = parseInt($("#total_day_gasoline").val()) || 0;
            let total_gasoline = num_amount_gasoline * total_day_gasoline;
            $("#total_gasoline").val(total_gasoline);
            $("#total_gasoline_label").text(total_gasoline);
            calulateNetPay();
        }

        function calulateTotal(){
            let total_earn_1 = parseInt($("#total_earn_1").val()) || 0;
            let total_earn_2 = parseInt($("#total_earn_2").val()) || 0;
            let total_earn_3 = parseInt($("#total_earn_3").val()) || 0;
            let total_earn_4 = parseInt($("#total_earn_4").val()) || 0;
            let total_earn_5 = parseInt($("#total_earn_5").val()) || 0;
            let total_leave_1 = parseInt($("#total_leave_1").val()) || 0;
            let total_leave_2 = parseInt($("#total_leave_2").val()) || 0;
            let total_leave_3 = parseInt($("#total_leave_3").val()) || 0;
            let total_leave_4 = parseInt($("#total_leave_4").val()) || 0;
            let total_leave_5 = parseInt($("#total_leave_5").val()) || 0;
            let total_finger_print = parseInt($("#total_finger_print").val()) || 0;
            let total_alloance = parseInt($("#total_alloance").val()) || 0;
            let total_incentive = parseInt($("#total_incentive").val()) || 0;
            let total_bonus_kny = parseInt($("#total_bonus_kny").val()) || 0;
            let total_bonus_pcb = parseInt($("#total_bonus_pcb").val()) || 0 ;

            let total   = total_earn_1
                        + total_earn_2
                        + total_earn_3
                        + total_earn_4
                        + total_earn_5
                        - total_leave_1
                        - total_leave_2
                        - total_leave_3
                        - total_leave_4
                        - total_leave_5
                        + total_finger_print
                        + total_alloance
                        + total_incentive
                        + total_bonus_kny
                        + total_bonus_pcb ;

            $("#total_total").val(total);
            $("#total_total_label").text(total);
            calulateBenefit();
        }

        function calulateBenefit(){
            let total = parseInt($("#total_total").val()) || 0;
            let num_dependent = parseInt($("#num_dependent").val()) || 0;
            let tax = 0;
            let bias_level = 0;
            let dependent = num_dependent * 150000 ;
            let taxable = total - dependent ;

            if (taxable < 1200001) { //salary 0 -> 1,200,000R
                tax = 0; // tax = 0 
                bias_level = 0; // bias_level 0
            } 
            else if (taxable < 2000001) { //salary 1,200,001 R -> 2,000,000 R
                tax = 5 / 100;
                bias_level = 60000;
            } 
            else if (taxable < 8500001) { //salary 2,000,001 R -> 8,500,000 R
                tax = 10 / 100;
                bias_level = 160000;
            } 
            else if (taxable < 12500001) { //salary 8,500,001 R -> 12,500,000 R
                tax = 15 / 100;
                bias_level = 585000;
            }
            else if (taxable > 12500000) { // salary > 12,500,000 R
                tax = 20 / 100;
                bias_level = 1210000;
            }

            let salary_tax = parseInt((taxable * tax) - bias_level) ;
            if (salary_tax < 0) {
                salary_tax = 0; // can't return money tax to staff
            }

            let after_tax = total - salary_tax ;

            $("#total_dependent").val(dependent);
            $("#total_dependent_label").text(dependent);

            $("#total_amount_taxable").val(taxable);
            $("#total_amount_taxable_label").text(taxable);

            $("#total_tax").val(salary_tax);
            $("#total_tax_label").text(salary_tax);

            $("#total_beneft_after").val(after_tax);
            $("#total_beneft_after_label").text(after_tax);
            calulateNetPay();
        }

        function calulateNetPay(){
            let total_beneft_after = parseInt($("#total_beneft_after").val()) || 0;
            let total_moto = parseInt($("#total_moto").val()) || 0;
            let total_wht_moto = parseInt($("#total_wht_moto").val()) || 0;
            let total_gasoline = parseInt($("#total_gasoline").val()) || 0;
            let total_settlement = parseInt($("#total_settlement").val()) || 0;
            let total_compensation = parseInt($("#total_compensation").val()) || 0;
            let total_pf_staff = parseInt($("#total_pf_staff").val()) || 0;
            let total_pf_company = parseInt($("#total_pf_company").val()) || 0;
            let total_seniority = parseInt($("#total_seniority").val()) || 0;
            let total_telephone = parseInt($("#total_telephone").val()) || 0;

            let total_net_pay   = total_beneft_after
                                + total_moto
                                + total_wht_moto
                                + total_gasoline
                                + total_settlement
                                + total_compensation
                                + total_pf_staff
                                + total_pf_company
                                + total_seniority
                                + total_telephone ;

            $("#net_pay").val(total_net_pay);
            $("#net_pay_label").text(total_net_pay);
            formartnumber();
        }

        function formartnumber(){
            // format number
            $('.money').text(function(index, value) {
                if (value < 0) {
                    return '-' + value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    ;
                }
                else {
                    return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    ;
                }
            });
        }

        $("#num_earn_1").on('change keyup', function(event) {
            let date = new Date($("#start_block_1").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_earn_1").val(total);
            $("#total_earn_1_label").text(total);
            calulateTotal();
        });

        $("#num_earn_2").on('change keyup', function(event) {
            let date = new Date($("#start_block_2").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_earn_2").val(total);
            $("#total_earn_2_label").text(total);
            calulateTotal();
        });

        $("#num_earn_3").on('change keyup', function(event) {
            let date = new Date($("#start_block_3").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_earn_3").val(total);
            $("#total_earn_3_label").text(total);
            calulateTotal();
        });

        $("#num_earn_4").on('change keyup', function(event) {
            let date = new Date($("#start_block_4").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_earn_4").val(total);
            $("#total_earn_4_label").text(total);
            calulateTotal();
        });

        $("#num_earn_5").on('change keyup', function(event) {
            let date = new Date($("#start_block_5").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_earn_5").val(total);
            $("#total_earn_5_label").text(total);
            calulateTotal();
        });

        $("#num_leave_1").on('change keyup', function(event) {
            let date = new Date($("#start_leave_1").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_leave_1").val(total);
            $("#total_leave_1_label").text(total);
            calulateTotal();
        });

        $("#num_leave_2").on('change keyup', function(event) {
            let date = new Date($("#start_leave_2").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_leave_2").val(total);
            $("#total_leave_2_label").text(total);
            calulateTotal();
        });

        $("#num_leave_3").on('change keyup', function(event) {
            let date = new Date($("#start_leave_3").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_leave_3").val(total);
            $("#total_leave_3_label").text(total);
            calulateTotal();
        });

        $("#num_leave_4").on('change keyup', function(event) {
            let date = new Date($("#start_leave_4").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_leave_4").val(total);
            $("#total_leave_4_label").text(total);
            calulateTotal();
        });

        $("#num_leave_5").on('change keyup', function(event) {
            let date = new Date($("#start_leave_5").val());
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseInt($(this).val()) || 0; 
            let total = parseInt(amount * amount_of_day);
            // console.log(base_salary, day, amount_of_day, total) || 0;
            $("#total_leave_5").val(total);
            $("#total_leave_5_label").text(total);
            calulateTotal();
        });

        $("#num_finger_print").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_finger_print").val(total);
            $("#total_finger_print_label").text(total);
            calulateTotal();
        });

        $("#num_alloance").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_alloance").val(total);
            $("#total_alloance_label").text(total);
            calulateTotal();
        });

        $("#num_incentive").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_incentive").val(total);
            $("#total_incentive_label").text(total);
            calulateTotal();
        });

        $("#num_bonus_kny").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_bonus_kny").val(total);
            $("#total_bonus_kny_label").text(total);
            calulateTotal();
        });

        $("#num_bonus_pcb").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_bonus_pcb").val(total);
            $("#total_bonus_pcb_label").text(total);
            calulateTotal();
        });

        $("#num_dependent").on('change keyup', function(event) {
            calulateBenefit();
        });

        $("#num_amount_moto").on('change keyup', function(event) {
            calulateMoto();
        });

        $("#total_day_moto").on('change keyup', function(event) {
            calulateMoto();
        });

        $("#num_wht_moto").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_wht_moto").val(total);
            $("#total_wht_moto_label").text(total);
            calulateNetPay();
        });

        $("#num_amount_gasoline").on('change keyup', function(event) {
            calulateGasoline();
        });

        $("#total_day_gasoline").on('change keyup', function(event) {
            calulateGasoline();
        });

        $("#num_settlement").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_settlement").val(total);
            $("#total_settlement_label").text(total);
            calulateNetPay();
        });

        $("#num_compensation").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_compensation").val(total);
            $("#total_compensation_label").text(total);
            calulateNetPay();
        });

        $("#num_seniority").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_seniority").val(total);
            $("#total_seniority_label").text(total);
            calulateNetPay();
        });

        $("#num_telephone").on('change keyup', function(event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_telephone").val(total);
            $("#total_telephone_label").text(total);
            calulateNetPay();
        });


        $("#add_block_1").on('click', function(event) {
            $("#show_earn_2").removeAttr("hidden");
        });

        $("#add_block_2").on('click', function(event) {
            $("#show_earn_3").removeAttr("hidden");
        });

        $("#add_block_3").on('click', function(event) {
            $("#show_earn_4").removeAttr("hidden");
        });

        $("#add_block_4").on('click', function(event) {
            $("#show_earn_5").removeAttr("hidden");
        });

        $("#remover_block_2").on('click', function(event) {
            $("#show_earn_2").attr("hidden", true);
            $("#start_block_2").val("");
            $("#end_block_2").val("");
            $("#num_earn_2").val("");
            $("#total_earn_2_label").text("0");
            $("#total_earn_2").val("0");
            calulateTotal();
        });

        $("#remover_block_3").on('click', function(event) {
            $("#show_earn_3").attr("hidden", true);
            $("#start_block_3").val("");
            $("#end_block_3").val("");
            $("#num_earn_3").val("");
            $("#total_earn_3_label").text("0");
            $("#total_earn_3").val("0");
            calulateTotal();
        });

        $("#remover_block_4").on('click', function(event) {
            $("#show_earn_4").attr("hidden", true);
            $("#start_block_4").val("");
            $("#end_block_4").val("");
            $("#num_earn_4").val("");
            $("#total_earn_4_label").text("0");
            $("#total_earn_4").val("0");
            calulateTotal();
        });

        $("#remover_block_5").on('click', function(event) {
            $("#show_earn_5").attr("hidden", true);
            $("#start_block_5").val("");
            $("#end_block_5").val("");
            $("#num_earn_5").val("");
            $("#total_earn_5_label").text("0");
            $("#total_earn_5").val("0");
            calulateTotal();
        });
        

        $("#add_leave_1").on('click', function(event) {
            $("#show_leave_2").removeAttr("hidden");
        });

        $("#add_leave_2").on('click', function(event) {
            $("#show_leave_3").removeAttr("hidden");
        });

        $("#add_leave_3").on('click', function(event) {
            $("#show_leave_4").removeAttr("hidden");
        });

        $("#add_leave_4").on('click', function(event) {
            $("#show_leave_5").removeAttr("hidden");
        });

        $("#remover_leave_2").on('click', function(event) {
            $("#show_leave_2").attr("hidden", true);
            $("#start_leave_2").val("");
            $("#end_leave_2").val("");
            $("#num_leave_2").val("");
            $("#total_leave_2_label").text("0");
            $("#total_leave_2").val("0");
            calulateTotal();
        });

        $("#remover_leave_3").on('click', function(event) {
            $("#show_leave_3").attr("hidden", true);
            $("#start_leave_3").val("");
            $("#end_leave_3").val("");
            $("#num_leave_3").val("");
            $("#total_leave_3_label").text("0");
            $("#total_leave_3").val("0");
            calulateTotal();
        });

        $("#remover_leave_4").on('click', function(event) {
            $("#show_leave_4").attr("hidden", true);
            $("#start_leave_4").val("");
            $("#end_leave_4").val("");
            $("#num_leave_4").val("");
            $("#total_leave_4_label").text("0");
            $("#total_leave_4").val("0");
            calulateTotal();
        });

        $("#remover_leave_5").on('click', function(event) {
            $("#show_leave_5").attr("hidden", true);
            $("#start_leave_5").val("");
            $("#end_leave_5").val("");
            $("#num_leave_5").val("");
            $("#total_leave_5_label").text("0");
            $("#total_leave_5").val("0");
            calulateTotal();
        });

    });
</script>