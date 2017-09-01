$(document).ready(function(){
    'use strict';

    $('.resume-form').on('ajaxSuccess', function (event, message){
        $('.flash').removeClass('active');
        $('.flash.success').addClass('active');
    });
    $('.resume-form').on('ajaxError', function (event, message){
        $('.flash').removeClass('active');
        $('.flash.error').addClass('active');
    });

    $('.form-repeater').repeater({
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        },
    });
});
