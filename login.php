<script type="text/javascript">
    $(function() {
        var $successAlert = $("#success-alert");
        var successful = getParameterByName("success");

        if (successful) {
            $successAlert.html("Registration successful, you may now login.").offcanvas("show");
        }
    });
</script>

<div class="alert alert-success alert-fixed-top offcanvas" id="success-alert"></div>

<div class="row">
	<div class="col-md-6">
        <form action="login_redirect.php" method="post" class="form-horizontal" role="form">
            <?php if (isset($_SESSION["message"])): ?>
                <div class="form-group text-danger">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <?php echo $_SESSION["message"]; ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="form-group">
                <label for="username" class="col-sm-4 control-label">Username</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="username" name="username" maxlength="30">
                </div><!-- .col-sm-8 -->
            </div><!-- .form-group -->
            <div class="form-group">
                <label for="password" class="col-sm-4 control-label">Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password" name="password" maxlength="30">
                </div><!-- .col-sm-8 -->
            </div><!-- .form-group -->
            <div class="form-group">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <input type="submit" value="Sign In" name="submit" class="btn btn-primary"/>
                </div><!-- .col-sm-8 -->
            </div><!-- .form-group -->
        </form>
	</div><!-- .col-md-6 -->

	<div class="col-md-6">
        <?php if (isset($_SESSION["message"])): ?>
            <p style="visibility: hidden">Placeholder</p>
        <?php endif ?>
		<p>LIMTS is a web application that enables department chairs at Valparaiso University to schedule classes more effectively.</p>
		<p>Log in with your LIMTS username and password to get started. If you do not have an account click <a href="?page=register">here</a> to register.</p>
	</div><!-- .col-md-6 -->
</div>
