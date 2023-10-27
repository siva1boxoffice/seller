<?php 
$CI=& get_instance();
$CI->load->model('General_Model');
$notify_orders = $CI->General_Model->getOrderData_v1('notify')->result();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=900, initial-scale=1, shrink-to-fit=no"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="shortcut icon" href="<?php echo base_url().THEME_NAME;?>/images/favicon.ico" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/fontawesome.min.css?v=1.1.1">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/app.css?v=1.1.1">  
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/fonts.css?v=2.1.1.1">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/style.css?v=2.2.2.3">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/daterangepicker.css?v=2.2.1">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/responsive.css?v=2.2.2.8">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/select2.min.css?v=2.2.1.1">
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/mCustomScrollBox.css?v=2.2.1.1">
   <!--  <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/style1.css?v=2.2"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css?v=1.5.1">
     
  
  </head>
  <body>
    <!-- Header -->
    <?php //echo "<pre>";print_r($this->session->all_userdata());?>
    <header>
      <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light navbar_padd">
          <a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url().THEME_NAME;?>/images/logo.png" width="70px" alt="Logo"></a></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <?php if($this->session->userdata('role') == 1){ ?>

            <ul class="navbar-nav mr-auto header-center fs-15">
              <!--  <li class="nav-item <?php  if($this->uri->segment(4) == "upload_tickets"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>tickets/index/upload_tickets"><img src="<?php echo base_url().THEME_NAME;?>/images/upload.svg"> <span>Upload Tickets</span></a>
              </li> -->
              <li class="nav-item <?php  if($this->uri->segment(3) == "create_ticket"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>tickets/index/create_ticket"><img src="<?php echo base_url().THEME_NAME;?>/images/ticket.svg"> <span>Sell Tickets</span></a>
              </li>
             <!--  <?php if($this->session->userdata('other_event') == '1'){ ?>
              <li class="nav-item <?php  if($this->uri->segment(3) == "create_oe_ticket"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>tickets/index/create_oe_ticket"><img src="<?php echo base_url().THEME_NAME;?>/images/ticket.svg"> <span>Sell OE Tickets</span></a>
              </li>
              <?php } ?> -->
              <li class="nav-item <?php  if($this->uri->segment(3) == "listing"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>tickets/index/listing"><img src="<?php echo base_url().THEME_NAME;?>/images/list.svg">  <span>Listings</span></a>
              </li>
             <!--  <?php if($this->session->userdata('other_event') == '1'){ ?>
                <li class="nav-item <?php  if($this->uri->segment(3) == "oe_listing"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>tickets/index/oe_listing"><img src="<?php echo base_url().THEME_NAME;?>/images/list.svg">  <span>OE Listings</span></a>
              </li>
              <?php } ?> -->
              <li class="nav-item <?php  if($this->uri->segment(4) == "all"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>game/orders/list_order/all"><img src="<?php echo base_url().THEME_NAME;?>/images/cart-check.svg"> <span>Orders</span></a>
              </li>
            <!--   <li class="nav-item <?php  if($this->uri->segment(4) == "completed"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>game/orders/list_order/completed"><img src="<?php echo base_url().THEME_NAME;?>/images/cart-check.svg"> <span>Completed Orders</span></a>
              </li> -->
               <!-- <li class="nav-item <?php  if($this->uri->segment(1) == "payout"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>payout"><img src="<?php echo base_url().THEME_NAME;?>/images/paid.svg"> <span>Payout</span></a>
              </li> -->
              <?php if($this->session->userdata('admin_id') == 25){?>
               <li class="nav-item <?php  if($this->uri->segment(2) == "payment" || $this->uri->segment(2) == "payment_details"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>payout/payment"><img src="<?php echo base_url().THEME_NAME;?>/images/paid.svg"> <span>Payments</span></a>
              </li>
            <?php } ?>

             <?php if($this->session->userdata('seller_api') == '1'){ ?>
              <li class="nav-item <?php  if($this->uri->segment(4) == "logs/events"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>logs/"><img src="<?php echo base_url().THEME_NAME;?>/images/list.svg"> <span>Logs</span></a>
              </li>
               <?php 
            } ?>
            
            </ul>
            <?php } ?>
            <div class="form-inline my-2 my-lg-0 header-right">
              <ul >
                <li><a class="toolbar-link right-panel-trigger" data-panel="languages-panel" href="javascript:void(0);"><img src="<?php echo base_url().THEME_NAME;?>/images/uk-flag.svg"></a></li><!-- 
                <li><a href=""><img src="<?php echo base_url().THEME_NAME;?>/images/alert.svg"></a></li> -->
                 <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php echo base_url().THEME_NAME;?>/images/user.svg"></a>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a href="<?php echo base_url();?>home/myaccounts" class="dropdown-item is-media">
                      <div class="icon">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/user.svg">
                      </div>
                      <div class="meta">
                          <span>Profile</span>
                          <span>View your profile</span>
                      </div>
                  </a>
                  <a href="<?php echo base_url();?>tickets/bulk_tickets" class="dropdown-item is-media">
                      <div class="icon">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/ticket.svg">
                      </div>
                      <div class="meta">
                          <span>Bulk Ticket Listing</span>
                      </div>
                  </a>
                  <a href="<?php echo base_url();?>tickets/kyc" class="dropdown-item is-media">
                      <div class="icon">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/pdf2.svg">
                      </div>
                      <div class="meta">
                          <span>KYC Upload</span>
                      </div>
                  </a>
                  <hr class="dropdown-divider">
                  <div class="dropdown-item is-button">
                      <a href="<?php echo base_url();?>login/logout" class="button h-button is-primary is-raised is-fullwidth logout-button">
                          <!-- <span class="icon is-small">
                            
                        </span> -->
                          <span>Logout</span>
                      </a>
                  </div>

                  </div>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </header>

    
