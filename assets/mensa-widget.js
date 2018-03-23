/*jslint browser: true, unparam: true */
/*global jQuery, STUDIP */

(function ($, STUDIP) {
    'use strict';

    var data = {};

    STUDIP.OS_MENSA = {
        init: function () {
            data.url = $('meta[name="mensa-widget-url"]').attr('content');
            data.date = $('meta[name="mensa-widget-date"]').attr('content');
        },
        read: function (event, direction) {
            var date = data.date,
                that = this;
            STUDIP.OS_MENSA.getMenu(date, direction, function (disable) {
                if (disable) {
                    $(that).attr('disabled', true);
                } else {
                    $('.mensa-widget-back,.mensa-widget-forward').attr('disabled', false);
                }
            });

            event.preventDefault();
        },
        getMenu: function (date, direction, callback) {
            var timeout = setTimeout(function () {
                STUDIP.Overlay.show(true, '.mensa-widget');
            }, 200);

            data.date = date || data.date;

            $.ajax({
                url: [data.url, data.date, direction].join('/'),
                type: 'GET',
                cache: false
            }).done(function (response, status, jqxhr) {
                var title = jqxhr.getResponseHeader('X-Mensa-Widget-Title'),
                    disable = !!jqxhr.getResponseHeader('X-Mensa-Widget-Disable-Direction');

                $('.mensa-widget > section').replaceWith(response);
                $('.mensa-widget').closest('.studip-widget').find('.widget-header span:last').text(title);

                data.date = jqxhr.getResponseHeader('X-Mensa-Widget-Date');

                if (callback && $.isFunction(callback)) {
                    callback(disable);
                }

                $(document).trigger('mensa-widget.update');
            }).always(function () {
                clearTimeout(timeout);
                STUDIP.Overlay.hide();
            });
        }
    }

    // Setup
    $(document).ready(function () {
        STUDIP.OS_MENSA.init();
        $('.mensa-widget-forward').on('click', function(event) {
            STUDIP.OS_MENSA.read(event, 'next')
        });
        $('.mensa-widget-back').on('click', function(event) {
            STUDIP.OS_MENSA.read(event, 'previous')
        });
    })

}(jQuery, STUDIP));