"use strict";




    //====== Bootstrap Date-Picker js ======//
   

  

// date Range Picker
$('input[name="daterange"]').daterangepicker();
$(function() {
    $('input[name="birthdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        },
        function(start, end, label) {
            var years = moment().diff(start, 'years');
            alert("You are " + years + " years old.");
        });

    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });
    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        "drops": "up",
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    $('.input-daterange input').each(function() {
        $(this).datepicker();
    });
    $('#sandbox-container .input-daterange').datepicker({
        todayHighlight: true
    });
    $('.input-group-date-custom').datepicker({
        todayBtn: true,
        clearBtn: true,
        keyboardNavigation: false,
        forceParse: false,
        todayHighlight: true,
        defaultViewDate: {
            year: '2017',
            month: '01',
            day: '01'
        }
    });
    $('.multiple-select').datepicker({
        todayBtn: true,
        clearBtn: true,
        multidate: true,
        keyboardNavigation: false,
        forceParse: false,
        todayHighlight: true,
        defaultViewDate: {
            year: '2017',
            month: '01',
            day: '01'
        }
    });
    $('#config-demo').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": true,
        "timePicker24Hour": true,
        "timePickerSeconds": true,
        "showCustomRangeLabel": false,
        "alwaysShowCalendars": true,
        "startDate": "11/30/2016",
        "endDate": "12/06/2016",
        "drops": "up"
    }, function(start, end, label) {
        console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
    });
});
(function($) {

    $.fn.rkmd_checkboxRipple = function() {
        var self, checkbox, ripple, size, rippleX, rippleY, eWidth, eHeight;
        self = this;
        checkbox = self.find('.input-checkbox');

        checkbox.on('mousedown', function(e) {
            if (e.button === 2) {
                return false;
            }

            if ($(this).find('.ripple').length === 0) {
                $(this).append('<span class="ripple"></span>');
            }
            ripple = $(this).find('.ripple');

            eWidth = $(this).outerWidth();
            eHeight = $(this).outerHeight();
            size = Math.max(eWidth, eHeight);
            ripple.css({
                'width': size,
                'height': size
            });
            ripple.addClass('animated');

            $(this).on('mouseup', function() {
                setTimeout(function() {
                    ripple.removeClass('animated');
                }, 200);
            });

        });
    }

}(jQuery));

function change_checkbox_color() {
    $('.color-box .show-box').on('click', function() {
        $(".color-box").toggleClass("open");
    });

    $('.colors-list a').on('click', function() {
        var curr_color = $('main').data('checkbox-color');
        var color = $(this).data('checkbox-color');
        var new_colot = 'checkbox-' + color;

        $('.rkmd-checkbox .input-checkbox').each(function(i, v) {
            var findColor = $(this).hasClass(curr_color);

            if (findColor) {
                $(this).removeClass(curr_color);
                $(this).addClass(new_colot);
            }

            $('main').data('checkbox-color', new_colot);

        });
    });
}

