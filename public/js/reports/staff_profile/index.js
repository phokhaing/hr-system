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
/******/ 	return __webpack_require__(__webpack_require__.s = 590);
/******/ })
/************************************************************************/
/******/ ({

/***/ 590:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(591);


/***/ }),

/***/ 591:
/***/ (function(module, exports) {

$(document).ready(function () {
    $("#start_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-M-yy',
        yearRange: "-130:+1",
        // defaultDate: '-1yr',
        // maxDate: '+0d',
        onSelect: function onSelect(selected) {
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
            yearRange: "-70:+70"

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
            success: function success(data) {
                if (data.flag === 1) {
                    $("#listView").html("");
                    $("#listView").html(data.list);
                }
                $("#page-loading").fadeOut();
            },
            fail: function fail(err) {
                console.log(err);
            }

        });
    });
});

/***/ })

/******/ });