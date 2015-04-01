function loadSelector(type, handler, disableMultiselect, addBlank) {
   var url = "get_" + pluralize(type) + ".php";
   $.ajax({
        dataType: "json",
        url: url,
        success: function(data) {
            var selector = $("#" + type + "-selector");
            $.each(data, function(i, object) {
                var id = object.id,
                    abbr = object.name ? object.name : object.abbr;
                selector
                    .append($("<option>", { "value" : id, "internal-type" : type })
                    .text(abbr));
            });

            if (addBlank) {
                $("<option>", { value: '0', selected: true }).prependTo(selector);
            }

            if (!disableMultiselect) {
                selector.multiselect({
                    buttonWidth: "100%",
                    maxHeight: 200,
                    disableIfEmpty: true,
                    onChange: handler
                });
            } else {
                selector.change(handler);
            }
        }
    });
}

function ajaxLoadJSON(type, handler, data) {
    return $.ajax({
        dataType: "json",
        url: "get_" + pluralize(type) + ".php",
        data: data,
        success: function(objects) {
            if (objects.length) {
                $.each(objects, handler);
            } else {
                handler(0, objects);
            }
        }
    });
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}