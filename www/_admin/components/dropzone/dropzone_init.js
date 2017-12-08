$(function () {
    Dropzone.autoDiscover = false;

    App.dropzone = {};
    App.dropzone.forms = {};

    App.dropzone.convertResult = function(dropzone) {
        var hidden = $("#" + dropzone.hiddenId);

        var hiddenJson = hidden.attr("value");
        if (hiddenJson == "") {
            return;
        }

        var data = [];

        try {
            data = JSON.parse(hiddenJson);
        } catch (ex ){
        }

        $(dropzone.element).find(".dz-preview").each(function (order) {
            var el = $(this);

            $.each(data, function(i, v) {
                if (v === null) {
                    return;
                }

                var defaults = el.children(".data-div").attr("data-defaults");

                if (defaults === null || defaults === undefined || defaults == "") {
                    return;
                }

                defaults = JSON.parse(defaults);

                if (v["id"] == defaults["id"]) {
                    data[i]["order"] = order;
                    data[i]["delete"] = el.children(".dropzone-delete").attr("data-state") === "false" ? true : false;
                }
            });
        });

        hidden.attr("value", JSON.stringify(data));

        return data;
    };

    App.dropzone.changeDeleteState = function(jQueryElement) {
        var el = jQueryElement;

        var state = el.attr("data-state") === "true" ? true : false;

        if (state) {
            el.attr("data-state", "false");

            el.parent().css({"opacity": 0.5});
            el.css({"opacity": 1});

            el.text("Zrušit odstranění");
        } else {
            el.attr("data-state", "true");
            el.parent().css({"opacity": 1});
            el.text("Odstranit")
        }
    };

    App.dropzone.getAcceptedFiles = function (dropzone) {
         var accepted = [];

         $.each(dropzone.files, function (i, v) {
             var deleteIt = $(v.previewElement).children(".dropzone-delete").attr("data-state") !== "true" ? true : false;

             v.deleteIt = deleteIt;

             if (v.accepted && v.deleteIt === false) {
                 accepted.push(v);
             } else {
                 console.log($(v.previewElement).children(".data-div").attr("data-defaults"));

                 if ($(v.previewElement).children(".data-div").attr("data-defaults") === undefined) {
                     dropzone.removeFile(v);
                 }
             }
         });

         return accepted;
    };

    App.dropzone.containsFilesForUpload = function (dropzone) {
        var accteptedFiles = App.dropzone.getAcceptedFiles(dropzone);

        return accteptedFiles.length !== 0;
    };

    App.dropzone.runUpload = function (submitInput) {
        var frm = $(submitInput).closest("form");
        var btn = $(submitInput);

        var frmId = frm.attr("id");

        if (frmId in App.dropzone.forms) {
            var formValue = App.dropzone.forms[frmId];

            var length = 0;
            var processed = 0;

            $.each(formValue, function(i, v) {
                App.dropzone.convertResult(v);

                if (App.dropzone.containsFilesForUpload(v)) {
                    length++;
                }

                v.on("queuecomplete", function () {
                    processed++;

                    App.dropzone.convertResult(v);

                    if (processed === length) {
                        delete App.dropzone.forms[frmId];
                        btn.click();
                    }
                });

                v.processQueue();
            });

            if (length !== 0)  {
                return false;
            }
        }
    };

    $.nette.ext({
        before: function (data, settings, el) {
            var domObj;

            if (settings.nette) {
                domObj = $(settings.nette.el);

                if (domObj.prop("nodeName") === "INPUT" && domObj.attr("type") === "submit") {
                    var form = $(domObj).closest("form");
                    var dropzone = App.dropzone.forms[form.attr("id")];


                    if (dropzone !== undefined) {
                        App.dropzone.runUpload(domObj[0]);
                        return false;
                    }

                    return true;
                }
            }
        }
    });

    $("body").on("click", "input[type='submit']", function() {
        return App.dropzone.runUpload(this);
    });
});
