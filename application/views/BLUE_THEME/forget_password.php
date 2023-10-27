<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/app.css">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/fonts.css?v=2">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css?v=1.4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?v=1.4">
  </head>
  <body>
    <div class="login-page">
      <div class="login-left-side">
        <div class="login-logo text-center">
          <img src="<?php echo base_url().THEME_NAME;?>/images/logo.png" width="250px" alt="Logo">
        </div>
      </div>
      <div class="login-right-side fs-12">
        <div class="login-form">
          <h3 class="text-uppercase fs-24">Recover Account</h3>
          <h5 class="mb-4 fs-16">Reset your account password.</h5>
          <p>Enter your email and click on the confirm button to reset your password. We'll send you an email reset link to complete the procedure.</p>
          <form id="recover-form" class="forget_password" action="<?php echo base_url();?>login/send_password_reset_link">
            <div class="form-group">
              <label for="email">Email address</label>
               <input type="text" name="email_id" class="form-control" placeholder="Email email" required data-error="#errNm0">
               <span id="errNm0"></span>
            </div>
            <div class="row">
             <div class="col-lg-6">
               &nbsp;
              </div>
              <div class="col-lg-6">
                <p class="text-right">
                  <a href="<?php echo base_url();?>login/index" class="text-right forget_link">Remember your Password?</a>
                </p>
              </div>
            </div>
            <button type="submit" class="btn theme-btn btn-block text-uppercase fs-15">Confirm</button>
          </form>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url().THEME_NAME;?>/js/app.js"></script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/functions.js"></script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/jquery.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
     <script src="<?php echo base_url().THEME_NAME;?>/js/validate/jquery.validate.js?ver=2.4.8" async></script>
 <script src="<?php echo base_url().THEME_NAME;?>/js/validate/custom.js?ver=2.4.8" async></script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/popper.min.js"></script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
   
  </body>
</html>