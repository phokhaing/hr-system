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
/******/ 	return __webpack_require__(__webpack_require__.s = 586);
/******/ })
/************************************************************************/
/******/ ({

/***/ 586:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(587);


/***/ }),

/***/ 587:
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

    /**
     * Button reject resign request
     */
    $(".btn-reject-resign").on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to reject this request ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject !'
        }).then(function (result) {
            if (result.value) {

                $.ajax({
                    url: APP_URL + '/staff-resign/' + id + '/reject',
                    method: "GET",
                    dataType: "JSON",
                    data: {},
                    success: function success(data) {
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
                    fail: function fail(err) {
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
            onSelect: function onSelect(selected) {
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
    });
});

/***/ })

/******/ });