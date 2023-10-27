<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="shortcut icon" href="<?php echo base_url().THEME_NAME;?>/images/favicon.ico" type="image/x-icon" />
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
          <h3 class="text-uppercase fs-24">Sign In</h3>
          <h5 class="mb-4 fs-16">Welcome back to List my Tickets Sellers Lab</h5>
          <form id="login-form" class="admin_login" action="<?php echo base_url();?>login">
            <div class="form-group">
              <label for="email">Email address</label>
               <input type="email" class="form-control" name="username" placeholder="Email address" data-error="#errNm0" value="<?php if(isset($_COOKIE["username"]) && $_COOKIE["username"]!="") { echo $_COOKIE["username"]; } ?>" >
               <span id="errNm0"></span>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" data-error="#errNm1" value="<?php if(isset($_COOKIE["password"]) && $_COOKIE["password"]!="") { echo $_COOKIE["password"]; } ?>" >
              <span id="errNm1"></span>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="custom-control custom-checkbox">
                   <input name="remember_me" type="checkbox" class="custom-control-input" checked="<?php if(isset($_COOKIE["password"]) && $_COOKIE["password"]!="") { echo 'checked'; } ?>" >
                <label class="custom-control-label" for="customCheck1">Remember me</label>
              </div>
              </div>
              <div class="col-lg-6">
                <p class="text-right">
                  <a href="<?php echo base_url();?>login/forget_password" class="text-right forget_link">Forgot your Password?</a>
                </p>
              </div>
            </div>
            <button type="submit" class="btn theme-btn btn-block text-uppercase fs-15">LOGIN</button>
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