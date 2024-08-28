<!DOCTYPE html>
<html lang="en">
  <?php include './partials/constants.php'; ?>

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= htmlspecialchars($title) ?></title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
  <link rel="stylesheet" href="./assets/css/all.min.css">
  <script src="./assets/js/jquery.js"></script>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Register</h4>
              </div>
              <div class="card-body">
                <form id="registerForm">
                  <div class="form-group">
                    <label for="user_name">User Name</label>
                    <input id="user_name" type="text" class="form-control" name="username" autofocus>
                    <small class="text-danger" id="username_err"></small>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email">
                    <small class="text-danger" id="email_err"></small>
                  </div>
                  <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input id="mobile" type="text" class="form-control" name="mobile">
                    <small class="text-danger" id="mobile_err"></small>
                  </div>
                  <div class="row">
                    <div class="form-group col-6 password-wrapper">
                      <label for="password" class="d-block">Password</label>
                      <div class="input-group">
                        <input id="password" type="password" class="form-control" name="password">
                        <div class="input-group-append">
                          <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="fas fa-eye toggle-password"></i>
                          </span>
                        </div>
                      </div>
                      <small class="text-danger" id="password_err"></small>
                    </div>
                    <div class="form-group col-6">
                      <label for="password2" class="d-block">Password Confirmation</label>
                      <input id="password2" type="password" class="form-control" name="password_confirm">
                      <small class="text-danger" id="password2_err"></small>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Register
                    </button>
                  </div>
                </form>
              </div>
              <div class="mb-4 text-muted text-center">
                Already Registered? <a href="auth-login.php">Login</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="assets/js/page/auth-register.js"></script>
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>

  <script>
    $(document).ready(function() {
      $('#togglePassword').on('click', function() {
        const password = $('#password');
        const icon = $(this).find('i');
        if (password.attr('type') === 'password') {
          password.attr('type', 'text');
          icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          password.attr('type', 'password');
          icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('.text-danger').text('');

        // Collect form data
        const formData = {
          username: $('#user_name').val(),
          email: $('#email').val(),
          mobile: $('#mobile').val(),
          password: $('#password').val(),
          password_confirm: $('#password2').val()
        };

        // Send data via AJAX
        $.ajax({
          url: 'http://treading.softtronix.co.in/admin/api/api-register-admin.php', // Backend script to handle the data
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(formData),
          success: function(response) {
            if (response.status === 'success') {
              alert(response.message);
              window.location.href = 'auth-login.php'; // Redirect to login page
            } else {
              // Display validation errors
              if (response.message instanceof Array) {
                response.message.forEach(function(error) {
                  if (error.includes('Username')) {
                    $('#username_err').text(error);
                  }
                  if (error.includes('Email')) {
                    $('#email_err').text(error);
                  }
                  if (error.includes('Mobile')) {
                    $('#mobile_err').text(error);
                  }
                  if (error.includes('Password') && !error.includes('confirm')) {
                    $('#password_err').text(error);
                  }
                  if (error.includes('confirm')) {
                    $('#password2_err').text(error);
                  }
                });
              } else {
                alert(response.message);
              }
            }
          },
          error: function(xhr, status, error) {
            alert('An error occurred: ' + error);
          }
        });
      });
    });
  </script>
</body>

</html>
