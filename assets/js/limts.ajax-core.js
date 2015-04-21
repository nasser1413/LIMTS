function loadSelector(type, handler, opts) {
   // These are the default options
   var options = {
       multiselect: true,
       addBlank: false,
       inheritClass: true,
       disableIfEmpty: true,
       undisable: true,
       done: function() { },
       offset: ""
   };

   // Extend the default options with our user-provided ones
   $.each((opts || {}), function(key, value) {
       options[key] = value;
   });

   // Pluralize the URL
   var url = "getters/get_" + pluralize(type) + ".php";
   $.ajax({
        dataType: "json",
        url: url,
        success: function(data) {
            var selector = $("#" + type + "-selector" + options.offset);
            var filters = Filters.filters;
            $.each(data, function(i, object) {
                var id = object.id,
                    abbr = object.name ? object.name : object.abbr,
                    checked = $.inArray(id, filters[type]) !== -1;

                selector.append(
                    $("<option>", { "value" : id, "internal-type" : type })
                        .text(abbr).prop("selected", checked)
                );
            });

            if (options.offset) {
                selector.attr("offset", options.offset)
            }

            if (options.addBlank) {
                $("<option>", { value: '0', selected: true }).prependTo(selector);
            }

            if (options.undisable) {
                selector.prop("disabled", false);
            }

            if (options.multiselect) {
                selector.multiselect({
                    buttonWidth: "100%",
                    maxHeight: 200,
                    disableIfEmpty: options.disableIfEmpty,
                    inheritClass: options.inheritClass,
                    onChange: handler
                });
            } else {
                selector.change(handler);
            }

            options.done.apply(selector);
        }
    });
}

function loadRooms(onChange, opts) {
    // These are the default options
    var options = {
        multiselect: true,
        done: function() { },
        offset: ""
    };

    // Extend the default options with our user-provided ones
    $.each((opts || {}), function(key, value) {
        options[key] = value;
    });

    $.ajax({
        dataType: "json",
        url: "getters/get_buildings.php",
        success: function(buildings) {
            var selector = $("#room-selector" + options.offset);
            var results = [];
            $.each(buildings, function(i, building) {
                var async = $.ajax({
                    dataType: "json",
                    url: "getters/get_rooms.php",
                    data: {
                        building: building.id
                    },
                    success: function(rooms) {
                        if (rooms.length === 0) {
                            return;
                        }

                        var filters = Filters.filters,
                            optgroup = $("<optgroup>")
                                            .attr("label", building.description);

                        selector.append(optgroup);

                        $.each(rooms, function(i, room) {
                            optgroup.append(
                                    $("<option>")
                                        .val(room.id)
                                        .attr("internal-type", "room")
                                        .attr("capacity", room.cap)
                                        .prop("selected", $.inArray(room.id, filters.room) !== -1)
                                        .append(building.abbr + "-" + room.nmbr)
                            );
                        });
                    }
                 });
                results.push(async);
            });

            $.when.apply(this, results).done(function() {
                if (options.multiselect) {
                    selector.multiselect({
                        buttonWidth: "100%",
                        maxHeight: 200,
                        enableClickableOptGroups: true,
                        disableIfEmpty: true,
                        onChange: onChange
                    });
                } else {
                    selector.change(onChange);
                }

                options.done.apply(selector);
            });
        }
    });
}

function ajaxLoadJSON(type, handler, data) {
    return $.ajax({
        dataType: "json",
        url: "getters/get_" + pluralize(type) + ".php",
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
