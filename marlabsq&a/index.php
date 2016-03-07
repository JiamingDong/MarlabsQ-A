<?php
  // For Login
  // This page prints any errors associated with logging in
  // and it creates the entire login page

  // Include the header:
  $page_title = 'Start Page';
  include ('includes/header.html');

  // Print any error messages, if they exist:
  if (isset($errors) && !empty($errors)) {
    foreach ($errors as $msg) {
      echo "<div class=\"alert alert-danger\">
              <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
              <strong>Error:</strong> $msg
            </div>";
    }
    echo "<p style='color: orangered;'>Please try again.</p>";
  }

  // Print the success register message if a new account is created successfully.
  if (isset($success_register) && !empty($success_register)) {
    echo "<div id=\"successregisternotice\" class=\"alert alert-success\">
            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
            <strong>Register success!</strong> $success_register
          </div>";
  }
?>

<script type="text/javascript">
  window.setTimeout(function() {
    $("#successregisternotice").fadeTo(1500, 0).slideUp(500, function(){
      $(this).remove();
    });
  }, 1500);
</script>

<div class="container">
  <div class="row" style="padding-top: 60px;">
      <div class="col-md-4"></div>
      <div class="col-md-4">
        <a href="http://www.marlabs.com/" target="_blank">
          <img src="images/logo.jpg" alt="Marlabs Inc." style="width:100%;height:100%;">
        </a>
      </div>
      <div class="col-md-4"></div>
  </div>
  <div style="height: 50px;"></div>

  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-login">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <a href="#" class="active" id="login-form-link">Login</a>
            </div>
            <div class="col-xs-6">
              <a href="#" id="register-form-link">Register</a>
            </div>
          </div>
          <hr>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">

              <!-- Login Form -->
              <form id="login-form" action="login.php" method="post" role="form" style="display: block;">
                <div class="form-group">
                  <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" value="<?php
                  if(isset($_COOKIE['remember_me'])) {
                    echo $_COOKIE['remember_me'];
                  }
                  ?>">
                </div>
                <div class="form-group">
                  <input type="password" name="pass" id="pass" tabindex="2" class="form-control" placeholder="Password">
                </div>
                <div class="form-group text-center">
                  <input type="checkbox" tabindex="3" class="" name="remember" id="remember" <?php
                    if(isset($_COOKIE['remember_me'])) {
                      echo 'checked="checked"';
                    }
                    ?>>
                  <label for="remember"> Remember Me</label>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                      <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="text-center">
                        <a href="" tabindex="5" class="forgot-password">Forgot Password?</a>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

              <!-- Register Form -->
              <form id="register-form" action="register.php" method="post" role="form" style="display: none;">
                <div class="form-group">
                  <input type="text" name="first_name" id="first_name" tabindex="1" class="form-control" placeholder="First Name" value="">
                </div>
                <div class="form-group">
                  <input type="text" name="last_name" id="last_name" tabindex="1" class="form-control" placeholder="Last Name" value="">
                </div>
                <div class="form-group">
                  <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
                </div>
                <div class="form-group">
                  <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                </div>
                <div class="form-group">
                  <input type="password" name="confirm_password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
                </div>
                <div class="form-group">
                  <label>Class: </label>
                  <div id="mainselection">
                    <select name="class_type">
                      <option value="0">PHP</option>
                      <option value="1">Java</option>
                      <option value="2">BigData</option>
                      <option value="3">.NET</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label>Register as: </label>
                  <div id="mainselection">
                    <select name="user_type">
                      <option value="0">Trainee</option>
                      <option value="1">Trainer</option>
                    </select>
                  </div>
                </div>
                <div class="form-group" style="padding-top: 50px;">
                  <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                      <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
                    </div>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once ('includes/footer.html'); ?>

