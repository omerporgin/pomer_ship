window._ = require('lodash');

try {
    window.Popper = require('popper.js');

    window.$ = window.jQuery = require('jquery');

    require('bootstrap');

} catch (e) {
    console.log('/public/js/app.js');
    console.log(e);
}

import {Register} from './app/pages/register';
import {Dashboard} from './app/pages/dashboard';

$(function () {

    new Dashboard();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log("App ready!");

    if ($("#app_register").length > 0 || $("#app_dashboard").length > 0) {
        new Register;
    }

    const faqs = document.querySelectorAll(".faq");
    faqs.forEach(faq => {
        faq.addEventListener("click", () => {
            faq.classList.toggle("active");
        })
    })

});

$(window).scroll(function () {
    if ($(window).scrollTop() == 0) {
        $("header").removeClass("shadow-lg");
    } else {
        $("header").addClass("shadow-lg");
    }
})
