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
/******/ 	return __webpack_require__(__webpack_require__.s = 578);
/******/ })
/************************************************************************/
/******/ ({

/***/ 578:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(579);


/***/ }),

/***/ 579:
/***/ (function(module, exports) {

/**
 * Don't move this function to other file or cut to document.ready
 */
var get_location = function get_location() {
    /**
     * Set up token
     * ============
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Get location
     * ============
     */

    $("body").on('change', '.province_id', function (e) {
        e.preventDefault();

        var district_id = $(this).parent().parent().find('select.district_id');

        var p_id = $(this).val();

        $.ajax({
            url: APP_URL + "/unity/district/" + p_id,
            method: "GET",
            dataType: "HTML",
            data: "",
            success: function success(data) {
                $(district_id).html("");
                $(district_id).prepend("<option value=''>>> ស្រុក <<</option>");
                $(district_id).append(data);
            },
            fail: function fail(err) {
                console.log(err);
            }
        });
    });
    $("body").on('change', '.district_id', function (e) {
        e.preventDefault();

        var commune_id = $(this).parent().parent().find('select.commune_id');
        var d_id = $(this).val();

        $.ajax({
            url: APP_URL + "/unity/commune/" + d_id,
            method: "GET",
            dataType: "HTML",
            data: "",
            success: function success(data) {
                $(commune_id).html("");
                $(commune_id).prepend("<option value=''>>> ឃុំ <<</option>");
                $(commune_id).append(data);
            },
            fail: function fail(err) {
                console.log(err);
            }
        });
    });
    $("body").on('change', '.commune_id', function (e) {
        e.preventDefault();

        var village_id = $(this).parent().parent().find('select.village_id');
        var c_id = $(this).val();

        $.ajax({
            url: APP_URL + "/unity/village/" + c_id,
            method: "GET",
            dataType: "HTML",
            data: "",
            success: function success(data) {
                $(village_id).html("");
                $(village_id).prepend("<option value=''>>> ភូមិ <<</option>");
                $(village_id).append(data);
            },
            fail: function fail(err) {
                console.log(err);
            }
        });
    });
};

/*
* Jquery Dependency
* Currency format
*/
$("input[data-type='currency']").on({
    keyup: function keyup() {
        formatCurrency($(this));
    },
    blur: function blur() {
        formatCurrency($(this), "blur");
    }
});
function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.

    // get input value
    var input_val = input.val();

    // don't validate empty input
    if (input_val === "") {
        return;
    }

    // original length
    var original_len = input_val.length;

    // initial caret position
    var caret_pos = input.prop("selectionStart");

    // check for decimal
    if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
            right_side += "00";
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = left_side + "." + right_side;
        // input_val = "$" + left_side + "." + right_side;
    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = input_val;
        // input_val = "$" + input_val;

        // final formatting
        if (blur === "blur") {
            input_val += ".00";
        }
    }

    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}

$(document).ready(function () {

    get_location();

    $('.date').datepicker({
        dateFormat: 'dd-M-yy'
    });

    $("form").attr('autocomplete', 'off');
    $("input").attr('autocomplete', 'off');

    /**
     * Function Select-2 for filter data in view: list all staff
     */
    $(".js-select2-single").select2();
});

/***/ })

/******/ });