
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import $ from 'jquery';
window.$ = window.jQuery = $;

import "jquery-ui/ui/widgets/datepicker.js";
import "jquery-timepicker/jquery.timepicker.js";
import "select2";

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));
//
// const app = new Vue({
//     el: '#app'
// });

Echo.channel('winners')
    .listen('AnnounceWinner', (data) => {
        if (data.signup) {
            // var td = "<td colspan='4' class='text-center padding-20'><i class='fa fa-check-square-o fa-1x margin-right10' aria-hidden='true'></i>Winner has been drawn!</td>";
            $('#row-' + data.signup.raffle_id).html('');
            $('#row-' + data.signup.raffle_id).removeClass('bg-red');
            // $('#row-' + data.signup.raffle_id).addClass('bg-green');

            alertify.success("<i class='fa fa-check-square-o fa-1x margin-right10' aria-hidden='true'></i>Winner has been drawn!");

            $.ajax({
                url: '/raffle/reload/list',
                dataType: 'json',
                success: function (response) {
                    $('#raffle-list-container').html(response.data.list);
                }
            });
        } else {
            var td = "<td colspan='4' class='text-center padding-20'><i class='fa fa-times-circle fa-1x margin-right10' aria-hidden='true'></i>Oh snap! Something went wrong.</td>";
            $('#row-' + data.signup.raffle_id).html(td);

            alertity.error("<i class='fa fa-times-circle fa-1x margin-right10' aria-hidden='true'></i>Oh snap! Something went wrong.");
        }
    });