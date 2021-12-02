/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 584);
/******/ })
/************************************************************************/
/******/ ({

/***/ 584:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(585);


/***/ }),

/***/ 585:
/***/ (function(module, exports) {

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

    var alert_not_found = function alert_not_found() {
        Swal.fire('Not Found', 'The ID not found. Please review again !', 'warning', 'false');
    };

    var alert_duplicate = function alert_duplicate() {
        Swal.fire('Opp!', 'This user already movement! Please ID again.', 'warning', 'false');
    };

    var push_staff_info = function push_staff_info(obj) {
        $("#full_name_kh").val(obj.last_name_kh + " " + obj.first_name_kh);
        $("#full_name_en").val(obj.last_name_en + " " + obj.first_name_en);
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
        var input = $("#staff_id").val();

        // ID card at least 4 characters
        if (input.length >= 4) {
            $.ajax({
                url: "/staff-movement/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id: input
                },
                success: function success(data) {
                    // console.log(data);
                    if (data.flag === 1) {
                        //IF get data successfully

                        if (data.staff != null) {
                            //IF have data
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
                error: function error(err) {
                    console.log(err.message);
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
        var input = $("#transfer_to_id").val();

        // ID card at least 4 characters
        if (input.length >= 4) {
            $.ajax({
                url: "/staff-movement/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id: input
                },
                success: function success(data) {
                    if (data.flag === 1) {
                        //IF get data successfully

                        if (data.staff != null) {
                            //IF have data
                            $("#transfer_to_name").val(data.staff.last_name_kh + " " + data.staff.first_name_kh);
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
                fail: function fail(err) {
                    console.log(err.message);
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
        var input = $("#get_work_form_id").val();

        // ID card at least 4 characters
        if (input.length >= 4) {
            $.ajax({
                url: "/staff-movement/find",
                dataType: "JSON",
                method: "POST",
                data: {
                    emp_id: input
                },
                success: function success(data) {
                    if (data.flag === 1) {
                        //IF get data successfully

                        if (data.staff != null) {
                            //IF have data
                            $("#get_work_form_name").val(data.staff.last_name_kh + " " + data.staff.first_name_kh);
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
                fail: function fail(err) {
                    console.log(err.message);
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

/***/ })

/******/ });