<script>
    $(document).ready(function () {
        const KHR_CURRENCY = 'KHR';

        //** Define global variable */
        let clone_blocking_date_range = $("#blocking_date_range").clone();
        var total_pf_staff = 0;
        var total_pf_company = 0;
        var base_salary = 0;
        var pf_id = 0;
        var staff_personal_id;
        var contract_id;
        var totalEarned = 0;
        var isCompanyPaidTax = false;
        var currency = '';

        var days = function (month, year) {
            return new Date(year, month, 0).getDate();
        };

        $('#staff').select2({
            placeholder: "Staff ID, Name"
        });

        //Call checkData for init value
        checkData();

        //Start define functions
        //check if have data to enable button submit
        function checkData() {
            let pf_id = parseInt($("#pf_id").val()) || 0;
            let staff_personal_id = parseInt($("#staff_personal_id").val()) || 0;
            let contract_id = parseInt($("#contract_id").val()) || 0;
            console.log("checkData: " + pf_id + ", " + staff_personal_id + ", " + contract_id);

            if (staff_personal_id > 0 && contract_id > 0) {
                $("#btn_submit").removeAttr("disabled");
            } else {
                $("#btn_submit").attr("disabled", true);
            }
        }

        function calculateMotor() {
            let num_amount_moto = parseInt($("#num_amount_moto").val()) || 0;
            let total_day_moto = parseInt($("#total_day_moto").val()) || 0;
            let startDate = $("#start_moto").val();
            let daysOfMonth = getDaysInMonth(startDate);
            console.log("calculateMotor: " + daysOfMonth);
            let total_moto = (num_amount_moto / daysOfMonth) * total_day_moto;
            total_moto = checkToRoundAmount(total_moto);
            $("#total_moto").val(total_moto);
            $("#total_moto_label").text(total_moto.toFixed(2));
            calculateNetPay();
        }

        const getDaysInMonth = function (date) {
            console.log('getDaysInMonth: ' + date);
            if (date == null || date == '') {
                return 0;
            }
            let parseDate = new Date(date);
            return new Date(parseDate.getFullYear(), parseDate.getMonth() + 1, 0).getDate();
        };

        function calculateGasoline() {
            let num_amount_gasoline = parseInt($("#num_amount_gasoline").val()) || 0;
            let total_day_gasoline = parseInt($("#total_day_gasoline").val()) || 0;
            let startDate = $("#start_gasoline").val();
            let daysOfMonth = getDaysInMonth(startDate);
            console.log("calculateGasoline: " + daysOfMonth);
            let total_gasoline = (num_amount_gasoline / daysOfMonth) * total_day_gasoline;
            total_gasoline = checkToRoundAmount(total_gasoline);
            $("#total_gasoline").val(total_gasoline);
            4
            $("#total_gasoline_label").text(total_gasoline.toFixed(2));
            calculateNetPay();
        }

        //** Calculate total of salary before tax */
        function calculateTotal() {
            let retroSalary = parseInt($("#total_retro_salary").val()) || 0;

            let total_leave_1 = parseInt($("#total_leave_1").val()) || 0;
            let total_leave_2 = parseInt($("#total_leave_2").val()) || 0;
            let total_leave_3 = parseInt($("#total_leave_3").val()) || 0;
            let total_leave_4 = parseInt($("#total_leave_4").val()) || 0;
            let total_leave_5 = parseInt($("#total_leave_5").val()) || 0;
            let totalLeaveWithoutPay = total_leave_1 + total_leave_2 + total_leave_3 + total_leave_4 + total_leave_5;
            console.log("totalLeaveWithoutPay: " + totalLeaveWithoutPay);

            let total_finger_print = parseInt($("#total_finger_print").val()) || 0;
            let total_alloance = parseInt($("#total_alloance").val()) || 0;
            let total_incentive = parseInt($("#total_incentive").val()) || 0;
            let total_bonus_kny = parseInt($("#total_bonus_kny").val()) || 0;
            let total_bonus_pcb = parseInt($("#total_bonus_pcb").val()) || 0;

            let total = totalEarned;
            total += retroSalary;
            total += total_finger_print;
            total += total_alloance;
            total += total_incentive;
            total += total_bonus_kny;
            total += total_bonus_pcb;
            total -= totalLeaveWithoutPay;
            total = checkToRoundAmount(total);

            //** Calcualte total */
            $("#total_total").val(total);
            $("#total_total_label").text(total.toFixed(2));
            //** End Calcualte total */

            //** Calculate taxable */
            let dependent = parseInt($("#total_dependent").val()) || 0;
            let taxable = total - dependent;
            $("#total_amount_taxable").val(taxable);
            $("#total_amount_taxable_label").text(taxable.toFixed(2));
            //** End Calculate taxable */

            clearTaxOnSalary();
            calculateNetPay();
        }

        function calculateNetPay() {
            let total_benefit = parseFloat($("#total_beneft_after").val()) || 0;
            let total_moto = parseInt($("#total_moto").val()) || 0;
            let total_wht_moto = parseInt($("#total_wht_moto").val()) || 0;
            let total_gasoline = parseInt($("#total_gasoline").val()) || 0;
            let total_settlement = parseInt($("#total_settlement").val()) || 0;
            let total_compensation = parseInt($("#total_compensation").val()) || 0;
            let total_pf_staff = parseInt($("#total_pf_staff").val()) || 0;
            let total_pf_company = parseInt($("#total_pf_company").val()) || 0;
            let total_seniority = parseInt($("#total_seniority").val()) || 0;
            let total_telephone = parseInt($("#total_telephone").val()) || 0;

            //This condition to prevent when user did not click to checking tax
            if (total_benefit <= 0) {
                total_benefit = (parseFloat($("#total_total").val()) || 0);
            }

            let total_net_pay = total_benefit;
            total_net_pay += total_moto;
            total_net_pay += total_wht_moto;
            total_net_pay += total_gasoline;
            total_net_pay += total_settlement;
            total_net_pay += total_compensation;
            total_net_pay += total_pf_staff;
            total_net_pay += total_pf_company;
            total_net_pay += total_seniority;
            total_net_pay += total_telephone;

            let halfMonthAmount = parseFloat($("#half_month_amount").val()) || 0;
            total_net_pay -= halfMonthAmount;

            let fullMonthAmount = parseFloat($("#full_month_amount").val()) || 0;
            total_net_pay -= fullMonthAmount;

            if (isCompanyPaidTax) {
                total_net_pay += ($("#total_tax").val() || 0);
            }

            total_net_pay = checkToRoundAmount(total_net_pay);
            $("#net_pay").val(total_net_pay);
            $("#net_pay_label").text(total_net_pay.toFixed(2));
            formartnumber();
        }

        function formartnumber() {
            // format number
            $('.money').text(function (index, value) {
                // if (value < 0) {
                //     return '-' + value
                //     .replace(/\D/g, "")
                //     .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                //     ;
                // }
                // else {
                //     return value
                //     .replace(/\D/g, "")
                //     .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                //     ;
                // }

                //Seperates the components of the number
                var n = value.toString().split(".");
                //Comma-fies the first part
                n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                //Combines the two sections
                return n.join(".");
            });

        }

        function clearTaxOnSalary() {
            $("#total_tax_label").text(0);
            $("#total_tax").val(0);
            $("#tax_on_salary_rate").val(null);

            $("#total_beneft_after_label").text(0);
            $("#total_beneft_after").val(0);

            $("#net_pay_label").text(0);
            $("#net_pay").val(0);
        }

        /**Calculate total of leave without pay by date */
        function calculateTotalOfLeaveWithoutPay(date, totalDays) {
            month = date.getMonth() + 1;
            year = date.getFullYear();
            let day = days(month, year); // count days of month
            let amount_of_day = base_salary / day; // calculate salary for day of staff
            let amount = parseFloat(totalDays) || 0;
            let total = parseFloat(amount * amount_of_day);

            console.log('calculateTotalOfLeaveWithoutPay: ' + currency + total);
            total = checkToRoundAmount(total);
            return total;
        }

        /**End calcualte total of leave without pay */

        /** Round similar php round function */
        function round(value, exp) {
            if (typeof exp === 'undefined' || +exp === 0)
                return Math.round(value);

            value = +value;
            exp = +exp;

            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
                return NaN;

            // Shift
            value = value.toString().split('e');
            value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

            // Shift back
            value = value.toString().split('e');
            return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
        }

        /** End Round similar php round function */

        /** Check to round amount ex: 149 to 100 and 150 to 200 the same as calculate payroll of each transaction type. In case currency equal KHR */
        function checkToRoundAmount(amount) {
            if (currency == KHR_CURRENCY) {
                amount = round(amount, -2);
            }
            return amount;
        }

        /** End round amount function */

        /**
         * Bind final pay info from Edit Final Pay Page
         * This function has called when edit Final Pay
         * */
        function bindingFinalPayInfo(finalPay) {
            console.log(finalPay)
            $("#final_pay_id").val(finalPay.id);
            let finalPayObj = finalPay.json_data;

            //Set Leave without pay
            if (finalPayObj.leave_without_pay != null) {
                let firstLeave = finalPayObj.leave_without_pay[0];
                if (firstLeave.days != null) {
                    $("#start_leave_1").val(firstLeave.start_date);
                    $("#end_leave_1").val(firstLeave.end_date);
                    $("#num_leave_1").val(firstLeave.days);
                    $("#total_leave_1").val(firstLeave.amount);
                    $("#total_leave_1_label").text(-firstLeave.amount);
                }

                let secondLeave = finalPayObj.leave_without_pay[1];
                if (secondLeave.days != null) {
                    $("#show_leave_2").removeAttr("hidden");
                    $("#start_leave_2").val(secondLeave.start_date);
                    $("#end_leave_2").val(secondLeave.end_date);
                    $("#num_leave_2").val(secondLeave.days);
                    $("#total_leave_2").val(secondLeave.amount);
                    $("#total_leave_2_label").text(-secondLeave.amount);
                }

                let thirdLeave = finalPayObj.leave_without_pay[2];
                if (thirdLeave.days != null) {
                    $("#show_leave_3").removeAttr("hidden");
                    $("#start_leave_3").val(thirdLeave.start_date);
                    $("#end_leave_3").val(thirdLeave.end_date);
                    $("#num_leave_3").val(thirdLeave.days);
                    $("#total_leave_3").val(thirdLeave.amount);
                    $("#total_leave_3_label").text(-thirdLeave.amount);
                }

                let fourthLeave = finalPayObj.leave_without_pay[3];
                if (fourthLeave.days != null) {
                    $("#show_leave_4").removeAttr("hidden");
                    $("#start_leave_4").val(fourthLeave.start_date);
                    $("#end_leave_4").val(fourthLeave.end_date);
                    $("#num_leave_4").val(fourthLeave.days);
                    $("#total_leave_4").val(fourthLeave.amount);
                    $("#total_leave_4_label").text(-fourthLeave.amount);
                }

                let fifthLeave = finalPayObj.leave_without_pay[4];
                if (fifthLeave.days != null) {
                    $("#show_leave_5").removeAttr("hidden");
                    $("#start_leave_5").val(fifthLeave.start_date);
                    $("#end_leave_5").val(fifthLeave.end_date);
                    $("#num_leave_5").val(fifthLeave.days);
                    $("#total_leave_5").val(fifthLeave.amount);
                    $("#total_leave_5_label").text(-fifthLeave.amount);
                }
            }

            //Set Finger print
            let fingerPrintAmount = finalPayObj.figer_print.amount;
            let fingerPrintDesc = finalPayObj.figer_print.description;
            $("#finger_print").val(fingerPrintDesc);
            $("#num_finger_print").val(fingerPrintAmount);
            $("#total_finger_print").val(fingerPrintAmount);
            $("#total_finger_print_label").text(fingerPrintAmount);

            //Set Special Branch Allowance
            let branchAllowanceAmount = finalPayObj.specail_branch_alloance.amount;
            let branchAllowanceDesc = finalPayObj.specail_branch_alloance.description;
            $("#alloance").val(branchAllowanceDesc);
            $("#num_alloance").val(branchAllowanceAmount);
            $("#total_alloance").val(branchAllowanceAmount);
            $("#total_alloance_label").text(branchAllowanceAmount);

            //Set Incentive
            let incentiveAmount = finalPayObj.incentive.amount;
            let incentiveDesc = finalPayObj.incentive.description;
            $("#incentive").val(incentiveDesc);
            $("#num_incentive").val(incentiveAmount);
            $("#total_incentive").val(incentiveAmount);
            $("#total_incentive_label").text(incentiveAmount);

            //Set Bonus Khmer New Year
            let bonusKNYAmount = finalPayObj.bonus_kny.amount;
            let bonusKNYDesc = finalPayObj.bonus_kny.description;
            $("#bonus_kny").val(bonusKNYDesc);
            $("#num_bonus_kny").val(bonusKNYAmount);
            $("#total_bonus_kny").val(bonusKNYAmount);
            $("#total_bonus_kny_label").text(bonusKNYAmount);

            //Set Bonus Pchum Ben
            let bonusPCBAmount = finalPayObj.bonus_pcb.amount;
            let bonusPCBDesc = finalPayObj.bonus_pcb.description;
            $("#bonus_pcb").val(bonusPCBDesc);
            $("#num_bonus_pcb").val(bonusPCBAmount);
            $("#total_bonus_pcb").val(bonusPCBAmount);
            $("#total_bonus_pcb_label").text(bonusPCBAmount);

            //Set Less Dependents
            let dependentsAmount = finalPayObj.less_dependents.amount;
            let dependentNum = finalPayObj.less_dependents.number_of_spouse;
            let dependentsDesc = finalPayObj.less_dependents.description;
            $("#dependent").val(dependentsDesc);
            $("#num_dependent").val(dependentNum);
            $("#total_dependent").val(dependentsAmount);
            $("#total_dependent_label").text(dependentsAmount);

            //Set motorcycle rental
            let motorcycleStartDate = finalPayObj.motorcycle_rental.start_date;
            let motorcycleEndDate = finalPayObj.motorcycle_rental.end_date;
            let motorcycleAmount = finalPayObj.motorcycle_rental.amount;
            let motorcycleDays = finalPayObj.motorcycle_rental.days;
            let motorcycleTotal = finalPayObj.motorcycle_rental.total;
            $("#start_moto").val(motorcycleStartDate);
            $("#end_moto").val(motorcycleEndDate);
            $("#num_amount_moto").val(motorcycleAmount);
            $("#total_day_moto").val(motorcycleDays);
            $("#total_moto_label").text(motorcycleTotal);
            $("#total_moto").val(motorcycleTotal);

            //Set WHT on motorcycle rental
            let whtOnMotorCycleAmount = finalPayObj.wht_on_motorcycle_rental;
            $("#num_wht_moto").val(whtOnMotorCycleAmount);
            $("#total_wht_moto").val(whtOnMotorCycleAmount);
            $("#total_wht_moto_label").text(whtOnMotorCycleAmount);

            //Set Gasoline
            let gasolineStartDate = finalPayObj.gasoline.start_date;
            let gasolineEndDate = finalPayObj.gasoline.end_date;
            let gasolineAmount = finalPayObj.gasoline.amount;
            let gasolineDays = finalPayObj.gasoline.days;
            let gasolineTotal = finalPayObj.gasoline.total;
            $("#start_gasoline").val(gasolineStartDate);
            $("#end_gasoline").val(gasolineEndDate);
            $("#num_amount_gasoline").val(gasolineAmount);
            $("#total_day_gasoline").val(gasolineDays);
            $("#total_gasoline_label").text(gasolineTotal);
            $("#total_gasoline").val(gasolineTotal);

            //Set Settlement
            let settlementAmount = finalPayObj.settlement.amount;
            let settlementDesc = finalPayObj.settlement.description;
            $("#settlement").val(settlementDesc);
            $("#num_settlement").val(settlementAmount);
            $("#total_settlement").val(settlementAmount);
            $("#total_settlement_label").text(settlementAmount);

            //Set half month
            let halfMonthAmount = finalPayObj.half_pay.amount;
            let halfMonthDesc = finalPayObj.half_pay.description;
            $("#half_month").val(halfMonthDesc);
            $("#half_month_amount").val(halfMonthAmount);
            $("#label_half_month_amount").text(-halfMonthAmount);

            //Set full month
            let fullMonthAmount = finalPayObj.full_pay.amount;
            let fullMonthDesc = finalPayObj.full_pay.description;
            $("#full_month").val(fullMonthDesc);
            $("#full_month_amount").val(fullMonthAmount);
            $("#label_full_month_amount").text(-fullMonthAmount);

            //Set Compensation
            let compensationAmount = finalPayObj.compensation.amount;
            let compensationDesc = finalPayObj.compensation.description;
            $("#compensation").val(compensationDesc);
            $("#num_compensation").val(compensationAmount);
            $("#total_compensation").val(compensationAmount);
            $("#total_compensation_label").text(compensationAmount);

            //Set pension fund from company, because sometime pension fund from company was change by user
            if (finalPayObj.pension_fund != null) {
                $("#pf_staff").val(finalPayObj.pension_fund.pf_staff_description);

                let pfCompanyAmount = finalPayObj.pension_fund.acr_balance_company;
                let pfCompanyDesc = finalPayObj.pension_fund.pf_company_description;
                $("#pf_company").val(pfCompanyDesc);
                $("#num_pf_company").val(pfCompanyAmount);
                $("#total_pf_company").val(pfCompanyAmount);
                $("#total_pf_company_label").text(pfCompanyAmount);
            }

            //Set Seniority
            let seniorityAmount = finalPayObj.seniority.amount;
            let seniorityDesc = finalPayObj.seniority.description;
            $("#seniority").val(seniorityDesc);
            $("#num_seniority").val(seniorityAmount);
            $("#total_seniority").val(seniorityAmount);
            $("#total_seniority_label").text(seniorityAmount);

            //Set Telephone
            let telephoneAmount = finalPayObj.telephone.amount;
            let telephoneDesc = finalPayObj.telephone.description;
            $("#telephone").val(telephoneDesc);
            $("#num_telephone").val(telephoneAmount);
            $("#total_telephone").val(telephoneAmount);
            $("#total_telephone_label").text(telephoneAmount);
            calculateTotal();

            //Call checking tax to calculate
            $('#checking_tax').trigger('click');
        }

        function confirmPostFinalPay() {
            let netPay = $("#net_pay_label").text();
            Swal.fire({
                title: 'Confirm',
                text: 'តើអ្នកប្រាកដ៏ទេថាការគណនាប្រាក់ខែចុងក្រោយ [ NET PAY] ស្មើរនឹង ' + netPay + ' គឺត្រឹមត្រូវ?',
                type: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'ពិនិត្យឡើងវិញ',
                confirmButtonText: 'បាទ/ចាស់'
            }).then(function (isConfirm) {
                console.log(isConfirm);
                if (isConfirm.value) {
                    $("#post_final_pay").submit();
                }
            });
        }

        /** End define functions*/

        //Start observer action of all each column to update information
        $("#num_leave_1").on('change keyup', function (event) {
            let totalDays = $(this).val();
            let date = new Date($("#start_leave_1").val());
            let total = calculateTotalOfLeaveWithoutPay(date, totalDays);
            $("#total_leave_1").val(total);
            $("#total_leave_1_label").text(-total);
            calculateTotal();
        });

        $("#num_leave_2").on('change keyup', function (event) {
            let date = new Date($("#start_leave_2").val());
            let totalDays = $(this).val();
            let total = calculateTotalOfLeaveWithoutPay(date, totalDays);
            $("#total_leave_2").val(total);
            $("#total_leave_2_label").text(-total);
            calculateTotal();
        });

        $("#num_leave_3").on('change keyup', function (event) {
            let date = new Date($("#start_leave_3").val());
            let totalDays = $(this).val();
            let total = calculateTotalOfLeaveWithoutPay(date, totalDays);
            $("#total_leave_3").val(total);
            $("#total_leave_3_label").text(-total);
            calculateTotal();
        });

        $("#num_leave_4").on('change keyup', function (event) {
            let date = new Date($("#start_leave_4").val());
            let totalDays = $(this).val();
            let total = calculateTotalOfLeaveWithoutPay(date, totalDays);
            $("#total_leave_4").val(total);
            $("#total_leave_4_label").text(-total);
            calculateTotal();
        });

        $("#num_leave_5").on('change keyup', function (event) {
            let date = new Date($("#start_leave_5").val());
            let totalDays = $(this).val();
            let total = calculateTotalOfLeaveWithoutPay(date, totalDays);
            $("#total_leave_5").val(total);
            $("#total_leave_5_label").text(-total);
            calculateTotal();
        });

        $("#num_finger_print").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_finger_print").val(total);
            $("#total_finger_print_label").text(total);
            calculateTotal();
        });

        $("#num_alloance").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_alloance").val(total);
            $("#total_alloance_label").text(total);
            calculateTotal();
        });

        $("#num_incentive").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_incentive").val(total);
            $("#total_incentive_label").text(total);
            calculateTotal();
        });

        $("#num_bonus_kny").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_bonus_kny").val(total);
            $("#total_bonus_kny_label").text(total);
            calculateTotal();
        });

        $("#num_bonus_pcb").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_bonus_pcb").val(total);
            $("#total_bonus_pcb_label").text(total);
            calculateTotal();
        });

        $("#num_amount_moto").on('change keyup', function (event) {
            calculateMotor();
        });

        $("#total_day_moto").on('change keyup', function (event) {
            calculateMotor();
        });

        $("#num_wht_moto").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_wht_moto").val(total);
            $("#total_wht_moto_label").text(total);
            calculateNetPay();
        });

        $("#num_amount_gasoline").on('change keyup', function (event) {
            calculateGasoline();
        });

        $("#total_day_gasoline").on('change keyup', function (event) {
            calculateGasoline();
        });

        $("#num_settlement").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_settlement").val(total);
            $("#total_settlement_label").text(total);
            calculateNetPay();
        });

        $("#num_compensation").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_compensation").val(total);
            $("#total_compensation_label").text(total);
            calculateNetPay();
        });

        $("#num_seniority").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_seniority").val(total);
            $("#total_seniority_label").text(total);
            calculateNetPay();
        });

        $("#num_telephone").on('change keyup', function (event) {
            let total = parseInt($(this).val()) || 0;
            $("#total_telephone").val(total);
            $("#total_telephone_label").text(total);
            calculateNetPay();
        });


        $("#add_block_1").on('click', function (event) {
            $("#show_earn_2").removeAttr("hidden");
        });

        $("#add_block_2").on('click', function (event) {
            $("#show_earn_3").removeAttr("hidden");
        });

        $("#add_block_3").on('click', function (event) {
            $("#show_earn_4").removeAttr("hidden");
        });

        $("#add_block_4").on('click', function (event) {
            $("#show_earn_5").removeAttr("hidden");
        });

        $("#remover_block_2").on('click', function (event) {
            $("#show_earn_2").attr("hidden", true);
            $("#start_block_2").val("");
            $("#end_block_2").val("");
            $("#num_earn_2").val("");
            $("#total_earn_2_label").text("0");
            $("#total_earn_2").val("0");
            calculateTotal();
        });

        $("#remover_block_3").on('click', function (event) {
            $("#show_earn_3").attr("hidden", true);
            $("#start_block_3").val("");
            $("#end_block_3").val("");
            $("#num_earn_3").val("");
            $("#total_earn_3_label").text("0");
            $("#total_earn_3").val("0");
            calculateTotal();
        });

        $("#remover_block_4").on('click', function (event) {
            $("#show_earn_4").attr("hidden", true);
            $("#start_block_4").val("");
            $("#end_block_4").val("");
            $("#num_earn_4").val("");
            $("#total_earn_4_label").text("0");
            $("#total_earn_4").val("0");
            calculateTotal();
        });

        $("#remover_block_5").on('click', function (event) {
            $("#show_earn_5").attr("hidden", true);
            $("#start_block_5").val("");
            $("#end_block_5").val("");
            $("#num_earn_5").val("");
            $("#total_earn_5_label").text("0");
            $("#total_earn_5").val("0");
            calculateTotal();
        });


        $("#add_leave_1").on('click', function (event) {
            $("#show_leave_2").removeAttr("hidden");
        });

        $("#add_leave_2").on('click', function (event) {
            $("#show_leave_3").removeAttr("hidden");
        });

        $("#add_leave_3").on('click', function (event) {
            $("#show_leave_4").removeAttr("hidden");
        });

        $("#add_leave_4").on('click', function (event) {
            $("#show_leave_5").removeAttr("hidden");
        });

        $("#remover_leave_2").on('click', function (event) {
            $("#show_leave_2").attr("hidden", true);
            $("#start_leave_2").val("");
            $("#end_leave_2").val("");
            $("#num_leave_2").val("");
            $("#total_leave_2_label").text("0");
            $("#total_leave_2").val("0");
            calculateTotal();
        });

        $("#remover_leave_3").on('click', function (event) {
            $("#show_leave_3").attr("hidden", true);
            $("#start_leave_3").val("");
            $("#end_leave_3").val("");
            $("#num_leave_3").val("");
            $("#total_leave_3_label").text("0");
            $("#total_leave_3").val("0");
            calculateTotal();
        });

        $("#remover_leave_4").on('click', function (event) {
            $("#show_leave_4").attr("hidden", true);
            $("#start_leave_4").val("");
            $("#end_leave_4").val("");
            $("#num_leave_4").val("");
            $("#total_leave_4_label").text("0");
            $("#total_leave_4").val("0");
            calculateTotal();
        });

        $("#remover_leave_5").on('click', function (event) {
            $("#show_leave_5").attr("hidden", true);
            $("#start_leave_5").val("");
            $("#end_leave_5").val("");
            $("#num_leave_5").val("");
            $("#total_leave_5_label").text("0");
            $("#total_leave_5").val("0");
            calculateTotal();
        });

        $("#btn_submit").on('click', function (e) {
            e.preventDefault();

            //Validate to make sure tax on salry is okay
            let taxOnSalary = parseFloat($("#total_tax").val()) || 0;
            if (!taxOnSalary || taxOnSalary === 0) {
                Swal.fire({
                    title: 'Warining',
                    text: 'តើលោកអ្នកប្រាកដ៏ទេថាការគណនាពន្ធលើប្រាក់ខែ [TAX ON SALARY] ស្មើរនឹង 0.00 គឺត្រឹមត្រូវ?​ ',
                    type: 'warning',
                    showConfirmButton: true,
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'ពិនិត្យឡើងវិញ',
                    confirmButtonText: 'បាទ/ចាស់'
                })
                    .then(function (isConfirm) {
                        console.log(isConfirm);
                        if (isConfirm.value) {
                            confirmPostFinalPay();
                        }
                    });
            } else {
                confirmPostFinalPay();
            }
        });

        //** Scope Request Data from APIs */
        $('#staff').change(function () {
            console.log('staff change get staff info api');
            $(".loading").fadeIn("fast");
            //when change to set value to 0
            totalEarned = 0;
            currency = '';
            $(".data").val("");
            $(".value-data").val(0);
            $(".money").text("0");

            const staff_id = $(this).val();
            const selectedContractId = $(this).find(':selected').data('contract-id');
            console.log(staff_id + ', ' + selectedContractId)

            $.ajax({
                type: 'get',
                url: "{{ route('final_pay.get_staff_info') }}",
                data: {
                    'id': staff_id,
                    'contract_id': selectedContractId
                },
                success: function (data) {
                    $(".loading").fadeOut("fast");
                    if (data.status === 1) {
                        //console.log(data);
                        var obj = JSON.parse(data.data);
                        base_salary = obj.base_salary || 0;
                        currency = obj.currency;
                        isCompanyPaidTax = data.data.is_company_paid_tax || false;
                        console.log(base_salary);
                        console.log(obj);

                        $("#first_name_kh").val(obj.staff["first_name_kh"]);
                        $("#last_name_kh").val(obj.staff["last_name_kh"]);
                        $("#first_name_en").val(obj.staff["first_name_en"]);
                        $("#last_name_en").val(obj.staff["last_name_en"]);
                        $("#marital_status").val(obj.marital_status);
                        $("#gender").val(obj.sex);
                        $("#position").val(obj.currentPosition);
                        $("#doe").val(obj.start_date);

                        //Set salary earn from
                        let blockFromDate = obj.block_from_date;
                        let blockUntilDate = obj.block_until_date;
                        console.log(obj.block_date_range);

                        $("#blocking_date_range").remove();
                        let reverse = obj.block_date_range.reverse();
                        let i = reverse.length;
                        //Loop to add salary block from by month base on user selected date from and date to in form Last Day/Block Salary
                        $.each(reverse, function (index, value) {
                            console.log('block_date_range' + value);
                            let blocking_date_range = clone_blocking_date_range.clone();

                            blocking_date_range.find("#salary_earned_from_label").text('Salary Block From ' + i + ' :');
                            blocking_date_range.find("#block_from_date").val($.datepicker.formatDate('dd-M-yy', new Date(value.first_date_of_month)));
                            blocking_date_range.find("#block_end_date").val($.datepicker.formatDate('dd-M-yy', new Date(value.last_date_of_month)));
                            blocking_date_range.find("#total_days_label").val(value.total_days);
                            blocking_date_range.find("#earned_from_amount").text(value.earned_salary.toFixed(2));
                            blocking_date_range.find("#block_days_of_month").val(value.days_in_month);
                            blocking_date_range.find("#block_total_days").val(value.total_days);
                            blocking_date_range.find("#total_earn").val(value.earned_salary);
                            totalEarned += value.earned_salary;

                            $('#container_blocking_date_range > tbody').prepend(blocking_date_range);
                            i--;
                        });
                        //End set salary earn from


                        total_pf_company = parseInt(obj.acr_balance_company) || 0;
                        if (obj.pensionfund != null) {
                            total_pf_staff = parseInt(obj.pensionfund["json_data"]["acr_balance_staff"]) || 0;
                            pf_id = parseInt(obj.pensionfund["id"]) || 0;
                        }

                        staff_personal_id = parseInt(obj.staff["id"]);
                        contract_id = parseInt(obj.contract_id);

                        $("#pf_company").val((obj.interest_rate * 100) + "%");
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

                        //Set spouse
                        $("#num_dependent").val(obj.spouse_count);
                        $("#total_dependent_label").text(-obj.spouse_amount);
                        $("#total_dependent").val(obj.spouse_amount);
                        //End set spouse

                        //Set half month
                        $("#half_month").val(obj.half_month_desc);
                        $("#label_half_month_amount").text(-obj.half_month_amount);
                        $("#half_month_amount").val(obj.half_month_amount);
                        //End set half month

                        //Set full month
                        $("#full_month").val(obj.full_month_desc);
                        $("#label_full_month_amount").text(-obj.full_month_amount);
                        $("#full_month_amount").val(obj.full_month_amount);
                        //End set full month

                        //Set retro salary
                        $("#retro_salary_desc").val(obj.retro_salary_desc);
                        $("#retro_salary_total_days").val(obj.retro_salary_total_days);
                        $("#total_retro_salary_label").text(obj.retro_salary_amount);
                        $("#total_retro_salary").val(obj.retro_salary_amount);
                        //End set retro salary


                        calculateTotal();
                        checkData();

                        //Check to bind final pay info from Edit Final Pay Page
                        //When a navigation from edit page will have final_pay object
                        if (obj.final_pay != null) {
                            bindingFinalPayInfo(obj.final_pay)
                        }
                    } else {
                        //Handle error message here
                        console.log(data.message);
                        //TODO: clear all input here
                    }
                }
            });
        });

        $("#num_pf_staff").on('keyup', function(){
            let total_pf = $(this).val();
            $("#total_pf_staff").val(total_pf);
            $("#total_pf_staff_label").text(total_pf);
            calculateTotal();
        });

        $("#num_pf_company").on('keyup', function () {
            let total_pf_company = $(this).val();
            $("#total_pf_company").val(total_pf_company);
            $("#total_pf_company_label").text(total_pf_company);
            calculateTotal();
        });

        $('#checking_tax').on('click', function () {
            $(".loading").fadeIn("fast");
            console.log('============================= Call API checking tax');
            let salaryBeforeTax = parseFloat($("#total_total").val()) || 0;
            let spouseAmount = parseFloat($("#total_dependent").val()) || 0;
            console.log('call checking tax API: salaryBeforeTax: ' + salaryBeforeTax + ', spouseAmount:' + spouseAmount);
            $.ajax({
                type: 'post',
                url: "{{ route('final_pay.checkingTax') }}",
                data: {
                    'contract_id': contract_id,
                    'salary_before_tax': salaryBeforeTax,
                    'spouse_amount': spouseAmount
                },
                success: function (data) {
                    $(".loading").fadeOut("fast");
                    if (data.status === 1) {
                        var items = JSON.parse(data.data);
                        let taxOnSalary = items.tax_on_salary;
                        let taxOnSalaryRate = items.tax_rate
                        $("#total_tax_label").text(-taxOnSalary.toFixed(2));
                        $("#total_tax").val(taxOnSalary);
                        $("#tax_on_salary_rate").val(taxOnSalaryRate);

                        //** Calculate salary after tax */
                        let salaryAfterTax = salaryBeforeTax - taxOnSalary;
                        salaryAfterTax = checkToRoundAmount(salaryAfterTax);
                        console.log('call api checking tax is successfully: ' + salaryAfterTax);
                        $("#total_beneft_after_label").text(salaryAfterTax.toFixed(2));
                        $("#total_beneft_after").val(salaryAfterTax);

                        calculateNetPay();
                    } else {
                        //Handle error message here
                        console.log('call api checking tax is failed');
                        console.log("Error checking tax: " + data.message);
                        clearTaxOnSalary();
                    }
                    console.log('============================= End Call API checking tax');
                }
            });
        });

        //** End scope request data from APIs */
        //End observer action of all each column to update information

    });
</script>