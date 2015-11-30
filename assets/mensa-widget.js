/*jslint browser: true, unparam: true */
/*global jQuery, STUDIP */

(function ($, STUDIP) {
    'use strict';

    var data = {};

    function xprintf(str) {
        var args = Array.prototype.slice.call(arguments, 1);
        return str.replace(/\{(\d+)\}/g, function (match, index) {
            return args[index] || index;
        });
    }

    function getMenu(date, direction, callback) {
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

    $(document).on('click', '.mensa-widget footer a', function (event) {
        $(this).closest('li').addClass('current').siblings().removeClass('current');

        getMenu(null);

        event.preventDefault();
    }).on('click', '.mensa-widget-back,.mensa-widget-forward', function (event) {
        var date = data.date,
            that = this,
            direction = $(this).is('.mensa-widget-back') ? 'previous' : 'next';
        getMenu(date, direction, function (disable) {
            if (disable) {
                $(that).attr('disabled', true);
            } else {
                $('.mensa-widget-back,.mensa-widget-forward').attr('disabled', false);
            }
        });

        event.preventDefault();
    }).on('click', '.mensa-widget .mensa-peeker', function (event) {
        var labels = $(this).data().labels,
            visible = $(this).closest('table').toggleClass('peeking').is('.peeking');

        $(this).text(visible ? labels.visible : labels.hidden);

        event.preventDefault();
    }).on('ready', function () {
        data.url = $('meta[name="mensa-widget-url"]').attr('content');
        data.date = $('meta[name="mensa-widget-date"]').attr('content');
    }).on('ready mensa-widget.update', function () {

        $('.mensa-widget > section > table:has(.hidden)').each(function () {
            var hidden_count = $('.hidden', this).length,
                label_hidden = xprintf(hidden_count === 1 ? "+{0} ausgeblendeter Eintrag" : "+{0} ausgeblendete Einträge".toLocaleString(), hidden_count),
                label_visible = xprintf(hidden_count === 1 ? "-{0} ausgeblendeter Eintrag" : "-{0} ausgeblendete Einträge".toLocaleString(), hidden_count),
                toggle = $('<a href="#" class="mensa-peeker">').text(label_hidden).data('labels', {
                    hidden: label_hidden,
                    visible: label_visible
                });

            $('thead th', this).prepend(toggle);
        });
    });

// Config

    $(document).on('click', '.mensa-increases .toggle-new', function (event) {
        $(this).closest('table').find('.new-entry').toggle();
        event.preventDefault();
    }).on('click', '.mensa-increases table.collapsable .collapsable-toggle', function (event) {
        $(this).closest('table.collapsable').toggleClass('uncollapsed');
        event.preventDefault();
    }).on('ready ajaxComplete', function (event) {
        $('.mensa-increases table.collapsable:not(:has(a.collapsable-toggle))').each(function () {
            var toggle = $('<a href="#" class="collapsable-toggle">');
            $('thead th:first', this).wrapInner(toggle);
        });
    });

}(jQuery, STUDIP));