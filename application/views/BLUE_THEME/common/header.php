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
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;1,800;1,900&display=swap" rel="stylesheet"> -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/fontawesome.min.css?v=1.1.1">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/app.css?v=1.1.1">  
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/fonts.css?v=2.1.1.1">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/style.css?v=2.3.93">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/daterangepicker.css?v=2.2.1">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/responsive.css?v=2.2.91">
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/select2.min.css?v=2.2.1.1">
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/mCustomScrollBox.css?v=2.2.1.1">
   <!--  <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/style1.css?v=2.2"> -->
    <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/sweetalert2.min.css?v=1.5.1">

     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.css">
     
  
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
              <?php //if($this->session->userdata('admin_id') == 25){?>
               <li class="nav-item <?php  if($this->uri->segment(2) == "payment" || $this->uri->segment(2) == "payment_details"){ ?> active <?php } ?>">
                <a class="nav-link" href="<?php echo base_url();?>payout/payment"><img src="<?php echo base_url().THEME_NAME;?>/images/paid.svg"> <span>Payments</span></a>
              </li>
            <?php //} ?>

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
                <?php //if($this->session->userdata('admin_id') == 25 || $this->session->userdata('admin_id') == 11){?>
                <a href="<?php echo base_url();?>kyc/documents" class="dropdown-item is-media">
                      <div class="icon">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/pdf2.svg">
                      </div>
                      <div class="meta">
                          <span>Document Upload</span>
                      </div>
                  </a>
                  <a href="<?php echo base_url();?>payout/accounts" class="dropdown-item is-media">
                      <div class="icon">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/paid.svg">
                      </div>
                      <div class="meta">
                          <span>Payout Accounts</span>
                      </div>
                  </a>
                  
                <?php //} ?>
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

    
<div class="my_modal modal_widd">
 <div class="modal fade" id="myModal_seller_report_issue" role="dialog" >
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <!-- <h4 class="modal-title">Modal Header</h4> -->
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="upload_docs">
                    <h3>Report Event Issue</h3>
                  </div>
                  <div class="team_name">
                      <h3 id="report_match_name"></h3>
                      <p id="report_match_date"></p>
                      <p><span><span id="report_match_venue"></span></p>

                     <!--  <div class="form-group">
                        <label>Event ID</label>
                        <div class="input-group">
                          <input type="text" class="form-control valid" placeholder="3813731">
                        </div>
                      </div> -->
                      <div class="eve_issue">
                        <p>Please use this form to inform us of any event issues. This could include if an event has been postponed/cancelled. Likewise if our venue map doesn’t match your tickets. Please be as clear and concise as possible.</p>
                        <p>This form doe’s not write to Seller Support, so please do not ask questions relating to an order or listing. If your question is specific to an order or listing, please use the “Contact Us” link.</p>
                      </div>
                  <form id="report_issue" novalidate action="<?php echo base_url(); ?>game/report_issue"  method="post">
                  <div class="docs_type">
                    <label>Ticket Separation</label>
                      <select name="issue"  id="issue" class="ticket_home_down custom-select" required>
                        <option value="">Choose Issue</option>
                        <option value="5">Cancelled or Postponed Event</option>
                        <option value="1">Rescheduled Event</option>
                        <option value="2">Incorrect Venue Map</option>
                        <option value="3">Missing Ticket Category</option>
                        <option value="4">Other Issue</option>
                      </select>
                  </div>
                  <input type="hidden" name="report_match_id" id="report_match_id">
                  <input type="hidden" name="report_order_id" id="report_order_id">
                  <div class="btn_save_bttns mt-3">
                      <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-cancel">Cancel</button>
                      <button id="sub_submit" type="submit" form-id="" class="btn btn-primary ml-3">Confirm</button> 
                  </div>
                </form>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </div>
          
        </div>
      </div>
    </div>
  </div>

<div class="my_modal modal_widd">
      <div class="modal fade" id="myModal_tick_type" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <!-- <h4 class="modal-title">Modal Header</h4> -->
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="upload_docs">
                    <h3>Change Ticket Type</h3>
                  </div>
                  <div class="team_name">
                    <h3 id="ticket_match_name"></h3>
                    <p id="ticket_match_date"></p>
                    <p><span><span id="ticket_match_stadium"></span></span></p>
                    <div class="show_ticket">
                      <div class="list_ticket">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/tickets.svg" class="mCS_img_loaded"> <span class="tick_sts" id="ticket_match_tickets"></span>
                      </div>
                      <div class="tick_transfer" id="ticket_match_ticketstype">
                      
                      </div>
                    </div>

                    <div class="order_category">
                      <div class="order_cat_section">
                      <span class="od_bold">Section:</span>  <span id="ticket_match_section"></span> </div>
                      <div class="order_row">
                      <span class="od_bold">Block:</span><span id="ticket_match_block"></span></div>
                      <div class="order_row">
                      <span class="od_bold">Row:</span> <span id="ticket_match_row"></span></div>
                    </div>
                  </div>
                  <form id="change_ticket_type" novalidate action="<?php echo base_url(); ?>game/change_ticket_type"  method="post">
                  <div class="docs_type">
                    <label>New Ticket Type</label>
                      <select name="ticket_type" data-ticket="ticket_ticket_type" id="ticket_type" class="ticket_home_down custom-select">
                        <option value="">Please select ticket type</option>
                          <option value="1">Season card</option>
                          <option value="3">Paper Ticket</option>
                          <option value="2" selected="selected">E-Ticket</option>
                          <option value="4">Mobile Ticket</option>
                      </select>
                      <input type="hidden" name="bg_id" id="bg_id">
                  </div>

                  <div class="btn_save_bttns mt-3">
                      <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-cancel">Cancel</button>
                      <button id="sub_submit" type="submit" form-id="" class="btn btn-primary save_ticket_form ml-3">Confirm</button> 
                  </div>
                </form>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
