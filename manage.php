<script type="text/javascript">
    var type = getParameterByName("type");

    $(function() {
        var keys = [];

        var response = ajaxLoadJSON(type, function(i, obj) {
            var id = (obj.id || obj.database_id),
                row = $("<tr>").attr("id", type + id);

            if (Object.keys(obj).length > keys.length) {
                keys = Object.keys(obj)
            }

            $.each(obj, function(key, value) {
                if (key === "id" || key == "database_id") {
                    return;
                }

                row.append(
                    $("<td>")
                        .text(value)
                        .addClass(type + "-" + key)
                );
            });

            row.append(
                $("<td>").append(
                    $("<a>").append(
                        $("<span>")
                            .addClass("glyphicon glyphicon-edit")
                    ).attr("href", "?page=add-"+type+"&edit="+id)
                )
            );

            row.append(
                $("<td>").append(
                    $("<a>").append(
                        $("<span>")
                            .addClass("glyphicon glyphicon-remove")
                    ).attr("href", "?page=remove-"+type+"&id="+id)
                )
            );

            $("#content-body").append(row);
        });

        $.when(response).done(function() {
            $.each(keys, function(i, key) {
                if (key === "id" || key == "database_id") {
                    return;
                }

                $("#content-head-row").append(
                    $("<th>")
                        .text(key)
                        .addClass("text-capitalize")
                );
            });

            $("#content-head-row").append(
                $("<th>")
                    .text("Edit")
            );

            $("#content-head-row").append(
                $("<th>")
                    .text("Remove")
            );
        });

        $("#content-filter").keyup(function () {
            var rex = new RegExp($(this).val(), "i");
            // Hide all of the rows
            $(".searchable tr").hide();
            // Only re-showing the approptriate ones
            $(".searchable tr").filter(function () {
                return rex.test($(this).text());
            }).show();
        })
    });
</script>

<div class="well">
    <div class="row">
        <div class="dropdown col-xs-3">
            <button class="btn btn-default dropdown-toggle" type="button" id="management-menu" data-toggle="dropdown" aria-expanded="true" style="width: 100%">
                Manage <span class="caret"></span>
            </button>

            <ul class="dropdown-menu" role="menu" aria-labelledby="management-menu" style="width: 100%">
                <li role="presentation"><a role="menuitem" tabindex="-1" href="?page=manage&type=section">Section</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="?page=manage&type=class">Class</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="?page=manage&type=professor">Professor</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="?page=manage&type=professortype">Professor Type</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="?page=manage&type=semester">Semester</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="?page=manage&type=building">Building</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="?page=manage&type=room">Room</a></li>
            </ul>
        </div>

        <div class="col-xs-6" style="float: right">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" aria-describedby="filter-addon" id="content-filter">
                <span class="input-group-addon" id="filter-addon">
                    <span class="glyphicon glyphicon-search"></span>
                    <span class="sr-only">Search</span>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <table class="table table-striped">
            <thead>
                <tr id="content-head-row"></tr>
            </thead>

            <tbody id="content-body" class="searchable"></tbody>
        </table>
    </div>
</div>
