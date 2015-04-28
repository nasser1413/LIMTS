<script type="text/javascript">
var editId = getParameterByName("edit"),
    loadedClass;
    
function onFormSubmitted() {
    var name = $("#class-name").val();
    var title = $("#class-title").val();
    var crdh = $("#credit-hours").val();
    var conth = $("#contact-hours").val();

    
    if (!name || !title || !crdh) {
        if (!name) {
            $("#class-name").parents(".form-group").addClass("has-error");
        }

        if (!title) {
            $("#class-title").parents(".form-group").addClass("has-error");
        }

        if (!crdh) {
            $("#credit-hours").parents(".form-group").addClass("has-error");
        }
        
        $("#warning-alert").offcanvas("show");
    } else {
        $.ajax({
		    dataType: "json",
		    url: "creators/create_class.php",
            data: {
                name: name,
                title: title,
                credithours: crdh,
                contacthours: conth,
                database_id: editId
            },
            success: function(data) {
                if (data.response !== "Success") {
                    alert(data.response);
                } else {
                    window.location.href = "?page=calendar" + location.hash;
                }
            }
        });
    }
}

$(function() {
    // If "edit", load the existing data
    if (editId) {
       $("#form-header").text("Edit Class");
        ajaxLoadJSON("class", function(i, classObj) {
            loadedClass = classObj;
            $("#class-name").val(classObj.name);
            $("#class-title").val(classObj.title);
            $("#credit-hours").val(classObj.credithours);
            $("#contact-hours").val(classObj.contacthours);
        }, {
            id: editId
        });
    }
    
    $("#class-name").change(function() {
        var parent = $("#class-name").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

    $("#class-title").change(function() {
        var parent = $("#class-title").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
    
    $("#credit-hours").change(function() {
        var parent = $("#credit-hours").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
});

</script>

<h1 id="form-header">Add Class</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
        <label for="className1">Class Name:</label>
        <input type="text" class="form-control" id="class-name" name="className">
	    <p class= "help-block"><i>For Example: CS-358</i></p>
    </div>

    <div class="form-group">
        <label for="classTitleName1">Class Title:</label>
        <input type="text" class="form-control" id="class-title" name="classTitleName">
	    <p class= "help-block"><i>For Example: Software Design &amp; Development</i></p>
    </div>

    <div class="form-group">
        <label for="creditHours1">Credit Hours:</label>
        <input type="text" class="form-control" id="credit-hours" name="creditHours">
    </div>

    <div class="form-group">
        <label for="contactHours1">Contact Hours:</label>
        <input type= "text" class="form-control" id="contact-hours" name="contactHours">
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>

