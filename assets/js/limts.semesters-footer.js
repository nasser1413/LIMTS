
function _SemestersFooter() {
    Object.defineProperty(this, "mode", {
        get: function() {
            return this._mode;
        },
        set: function(val) {
            if ((val === "checkboxes" || val === "multi-selector") && val !== this._mode) {
                this._mode = val;
                this.modechange();
            }
        }
    });

    Object.defineProperty(this, "activeSemesters", {
        get: function() {
            if (this.mode === "checkboxes") {
                return this._activeSemesters;
            } else {
                return undefined;
            }
        },
        set: function(val) {
            if (this.mode === "checkboxes") {
                this.semesterschange(val);
            }
        }
    });

    // NOTE: We can change this to something more elegant
    Object.defineProperty(this, "jumpto", {
        get: function() {
            if (this.mode === "checkboxes") {
                return function(date) {
                    if (this._jumpto) {
                        this._jumpto(date);
                    }
                }
            } else {
                return undefined;
            }
        },
        set: function(val) {
            if (this.mode === "checkboxes") {
                this._jumpto = val;
            }
        }
    });
}

_SemestersFooter.prototype.getActiveIds = function() {
    var ids = [];

    // TODO: I'm almost certain there is a more jQuery way to do this...
    $.each(this.activeSemesters, function(i, semester) {
        ids.push(semester.id);
    });

    return ids;
}

_SemestersFooter.prototype.modechange = function() {
    if (this.mode !== "checkboxes") {
        console.log("We currently only support checkboxes mode.");
    } else {
        this.initSelector();
    }
}


_SemestersFooter.prototype.checkboxchange = function(event) {
    var id = event.data;

    if ($("#semesters-group input:checkbox[value='"+id+"']").prop("checked")) {
        Filters.add("semester", id);
    } else {
        Filters.remove("semester", id);
    }
}

_SemestersFooter.prototype.semesterschange = function(val) {
    // Get and Empty the Checkboxes group
    var checkboxGroup = $("#semesters-group");
    var checkboxChange = this.checkboxchange;
    checkboxGroup.empty();

    // If we had semesters, remove them from the filters all-at-once
    if (this._activeSemesters) {
        Filters.remove("semester", this.getActiveIds());
    }

    // Actually update the active semesters
    this._activeSemesters = val;

    // Then, for each active semester
    $.each(this.activeSemesters, function(i, semester) {
        // Add a label w/ an appropriate input & text field
        checkboxGroup.append(
            $("<label>").addClass("checkbox-inline").append(
                $("<input>").attr("type", "checkbox").val(semester.id).prop("checked", true)
            ).append(
                document.createTextNode(semester.name)
            )
        );
        // Then, set the change handlers for the checkboxes
        checkboxGroup.find("input:checkbox[value='"+semester.id+"']").change(semester.id, checkboxChange);
    });

    // We do this at the end so there is only one "hashchange" event triggered
    Filters.add("semester", this.getActiveIds());
}

_SemestersFooter.prototype.semesterselected = function() {
    // TODO: This is a quick fix to make this work, it should be investigated
    var handler = SemestersFooter.jumpto;

    $("select option:selected").each(function() {
        var option = this;

        ajaxLoadJSON("semester", function(i, semester) {
            // TODO: This is *another* quick fix
            handler.apply(SemestersFooter, [moment.unix(semester.start)]);
            $("#semester-selector").val("0");
        }, {
            id: option.value
        });
    });
}

_SemestersFooter.prototype.initSelector = function() {
    loadSelector("semester", this.semesterselected, true, true);
}

var SemestersFooter = new _SemestersFooter();
SemestersFooter.mode = "checkboxes";
SemestersFooter.activeSemesters = [];
