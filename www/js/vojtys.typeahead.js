(function($, window, document, location, navigator) {

    /* jshint laxbreak: true, expr: true */
    "use strict";

    // init objects
    var Vojtys = window.Vojtys || {};
    Vojtys.Forms = Vojtys.Forms || {};

    // init variables
    var engine, remoteHost, template, empty;

    // check dependences
    if ($.fn.typeahead === undefined) {
        console.error('Plugin "typeahead.js" is missing! Run `bower install typeahead.js` and load bundled version.');
        return;
    } else if ($.nette === undefined) {
        console.error('Plugin "nette.ajax.js" is missing!.');
        return;
    }

    $.fn.vojtysFormsTypeahead = function() {

        return this.each(function() {
            var $this = $(this);

            var settings = $.extend({}, $.fn.vojtysFormsTypeahead.defaults, $this.data('settings'));

            // init vojtys typeahead
            if (!$this.data('vojtys-forms-typeahead')) {
                $this.data('vojtys-forms-typeahead', (new Vojtys.Forms.Typeahead($this, settings)));

                var originalValue = "";

                $this.on("typeahead:render", function (e, s) {
                    if (s === undefined) {
                        $this.parent().children(".tt-menu").hide();
                    }

                    originalValue = $this.val();
                });

                $this.on('typeahead:selected', function(event, selection) {
                    $this.typeahead("val", originalValue);

                    if (selection.cnt || selection.search) {
                        $this.closest("form").submit();
                        return;
                    }

                    if ("link" in selection) {
                        window.location.href = selection.link;
                        return;
                    }
                });

                $this.on("typeahead:select", function () {
                    $this.typeahead("val", originalValue);
                });

                $this.on("typeahead:cursorchange", function () {
                    $this.typeahead("val", originalValue);
                });
            }
        });
    };

    Vojtys.Forms.Typeahead = function($element, options) {

        var placeholder = $element.data('query-placeholder');
        var display = $element.data('display');
        var st = $element.parent().find('#result-template').html(); // suggestion template
        var et = $element.parent().find('#empty-template').html(); // empty template

        // compile templates with Handlebars
        st = (typeof(st) == "undefined") ? null : Handlebars.compile(st);
        et = (typeof(et) == "undefined") ? null : Handlebars.compile(et);

        var bh = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: $element.data('remote-url'),
                wildcard: placeholder
            },
            transform: function (data) {
                console.log(data);

                return data;
            }
        });

        $element.typeahead(
            options,
            {
                limit: options.limit,
                display: display,
                source: bh,
                templates: {
                    suggestion: st,
                    empty: et
                }
            });
    };

    Vojtys.Forms.Typeahead.load = function() {
        $('[data-vojtys-forms-typeahead]').vojtysFormsTypeahead();
    };

    // autoload typeahead
    Vojtys.Forms.Typeahead.load();

    /**
     * Default settings
     */
    $.fn.vojtysFormsTypeahead.defaults = {};

    // assign to DOM
    window.Vojtys = Vojtys;

    // init typeahead if nette.ajax is success
    $.nette.ext('VojtysTypeaheadLiveEvent', {
        success: function () {
            Vojtys.Forms.Typeahead.load();
        }
    });

    // return Objects
    return Vojtys;

    // Immediately invoke function with default parameters
})(jQuery, window, document, location, navigator);
