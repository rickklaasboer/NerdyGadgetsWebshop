const Cookie = require("js-cookie");

// Require bootstrap & bind bootstrap dependencies to window
try {
    window.Popper = require('popper.js');
    window.$ = window.jQuery = require('jquery')
    require('bootstrap')
} catch (e) {
    // do nothing
}

// Add slideDown and slideUp animation to bootstrap dropdowns.
$('.dropdown')
    .on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    })
    .on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });

// Stop the dropdown disappearing when clicked on
$("#cart-dropdown").click(function (e) {
    e.stopPropagation();
})

// Enable bootstrap tooltips globally
// https://getbootstrap.com/docs/4.0/components/tooltips/
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

// On document ready
$(document).ready(function () {
    $('#suggestion').modal('show');

    // If a scroll cookie has been set
    if (Cookie.get('scroll') !== null) {
        // Get the previous scroll position
        $(document).scrollTop(Cookie.get('scroll'))
        // Remove the previous scroll position
        Cookie.remove('scroll')
    }

    // When user clicks on a submit button
    // save the scroll position
    $('button[type=submit]').on("click", function () {
        Cookie.set('scroll', $(document).scrollTop())
    });
});