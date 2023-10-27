  <?php $this->load->view(THEME_NAME.'/common/header');?>
   <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
    <style type="text/css">
    .btn-uploaded, 
    .btn-upload-1{
      border-radius: 0;
      width: 100px;
      text-align: center;
    }
  </style>
    <div class="main">
      <div class="container">


        <div class="row">
          <div class="col-lg-12">
            <div class="mt-5">
              <div class="row">
                <div class="col-lg-6">
                  <h3 class="dash-welcome-head fs-24">Welcome back, <?php echo $this->session->userdata('admin_name'); ?> !</h3>
                  <p class="dash-welcome-text fs-16">We’re happy to see you again on your dashboard! </p>
                </div>
                 <?php  if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 6) { ?>
                <div class="col-lg-6 text-right">
                  <a href="<?php echo base_url();?>tickets/index/create_ticket" class="btn theme-btn text-uppercase fs-16"> <i class="fa fa-plus"></i> sell tickets</a>
                </div>
              <?php } ?>
            </div>
          </div>
          </div>
        </div>
         <?php  if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 6) { ?>
        <section class="mt-3" >
          <div class="row ">
            <div class="col-lg-3">
              <div class="dash-list">
                <p class="fs-18">Total Sales</p>
                  <!-- <span class="alert alert-primary"><img src="<?php echo base_url().THEME_NAME;?>/images/currency.svg"></span> -->
                  <span class="alert alert-primary"><i class="far fa-money-bill-alt"></i> </span>
                   <?php if(array_sum(array_column($confirmed_sales_gbp,'total_amount')) > 0){?>
                <h2 class="mt-3 fs-22">£ <?php echo number_format(array_sum(array_column($confirmed_sales_gbp,'total_amount')),2);?><!--  <?php echo number_format($confirmed_sales[0]->total_sales,2);?> --></h2>
                <?php } ?>
                <?php //if(array_sum(array_column($confirmed_sales_eur,'total_amount')) > 0){?>
                <h2 class="mt-3 fs-22">€ <?php echo number_format(array_sum(array_column($confirmed_sales_eur,'total_amount')),2);?><!--  <?php echo number_format($confirmed_sales[0]->total_sales,2);?> --></h2>
              <?php //} ?>
               <?php //if(array_sum(array_column($confirmed_sales_usd,'total_amount')) > 0){?>
                <h2 class="mt-3 fs-22">د.إ <?php echo number_format(array_sum(array_column($confirmed_sales_aed,'total_amount')),2);?><!--  <?php echo number_format($confirmed_sales[0]->total_sales,2);?> --></h2>
                <?php //} ?>
              <?php //if(array_sum(array_column($confirmed_sales_usd,'total_amount')) > 0){?>
                <h2 class="mt-3 fs-22">$ <?php echo number_format(array_sum(array_column($confirmed_sales_usd,'total_amount')),2);?><!--  <?php echo number_format($confirmed_sales[0]->total_sales,2);?> --></h2>
                <?php //} ?>
              </div>
            </div>

             <div class="col-lg-3">
              <div class="dash-list">
                <p class="fs-18">Tickets listed</p>
                <span class="alert alert-info"><img src="<?php echo base_url().THEME_NAME;?>/images/dash-ticket-1.svg"></span>
                <h2 class="mt-3 fs-22"><?php echo $listed_tickets;?></h2>
              </div>
            </div>


             <div class="col-lg-3">
              <div class="dash-list">
                <p class="fs-18">Total Orders</p>
                <span class="alert alert-warning"><img src="<?php echo base_url().THEME_NAME;?>/images/shopping.svg"></span>
                <h2 class="mt-3 fs-22"><?php echo $orders;?></h2>
              </div>
            </div>


             <div class="col-lg-3">
              <div class="dash-list">
                <p class="fs-18">Confirmed Orders</p>
                <span class="alert alert-success"><img src="<?php echo base_url().THEME_NAME;?>/images/shopping-2.svg"></span>
                <h2 class="mt-3 fs-22"><?php echo $confirmed_orders;?></h2>
              </div>
            </div>
          </div>
        </section>

        <section class="recent-orders" id="content_e3">
          <h3 class="dash-title fs-20 float-left">Orders</h3>
             <span class="float-right"><a href="<?php echo base_url();?>game/download_orders" class="download_orders"> <img src="<?php echo base_url().THEME_NAME;?>/images/icon_excel.svg" > Download orders on to an Excel file</a></span> 
            <table class="table table-striped">

              <tbody>
                <?php 
                if ($getMySalesData) {
                foreach ($getMySalesData as $getMySalesDa) {
                   $seller_notes =       $this->General_Model->get_seller_notes($getMySalesDa->listing_note,3);

                   $future = strtotime($getMySalesDa->match_date);
                    $now = time();
                    $timeleft = $future - $now;
                    $daysleft = round((($timeleft / 24) / 60) / 60);

                    $dateDiff = intval(($future - $now) / 60);
                    $hours = intval($dateDiff / 60);
                    $minutes = $dateDiff % 60;

                    $userTimezone = "Europe/London";
                    $timezone = new DateTimeZone($userTimezone);

                    $crrentSysDate = new DateTime(date('m/d/y h:i:s a'), $timezone);
                    $userDefineDate = $crrentSysDate->format('m/d/y h:i:s a');

                    $start = date_create($userDefineDate, $timezone);
                    $end = date_create(date('m/d/y h:i:s a', $future), $timezone);

                    $diff = date_diff($start, $end);

                    if ($daysleft <= 0) {
                    $event_left =  'Expired';
                    }
                    else if ($daysleft >= 7) {
                      $lefttime = round(($daysleft/7));
                    $event_left =  $lefttime . ' Weeks Left';
                    } else {
                    $event_left =  $daysleft . ' Days Left';
                    }
                  // echo "<pre>";print_r($seller_notes);
                ?>
               <tr style="cursor: pointer;" data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($getMySalesDa->booking_no); ?>" class="fs-15">
                  <td><a data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($getMySalesDa->booking_no); ?>" class=" <?php if ($getMySalesDa->booking_status == 4  || $getMySalesDa->booking_status == 5  || $getMySalesDa->booking_status == 6 || $getMySalesDa->delivery_status == 1 || $getMySalesDa->delivery_status == 2 || $getMySalesDa->delivery_status == 4 || $getMySalesDa->delivery_status == 5 || $getMySalesDa->delivery_status == 6) { ?>
                      btn fs-14 btn-warning btn-upload-1
                    <?php } else{ ?> btn btn-upload btn-uploaded fs-14 Upload <?php } ?>  order_details" style="color:#fff;">
                    <?php if ($getMySalesDa->booking_status == 4  || $getMySalesDa->booking_status == 5  || $getMySalesDa->booking_status == 6 || $getMySalesDa->delivery_status == 1 || $getMySalesDa->delivery_status == 2 || $getMySalesDa->delivery_status == 4 || $getMySalesDa->delivery_status == 5 || $getMySalesDa->delivery_status == 6) { ?>
                      Uploaded
                    <?php } else{ ?> Upload <?php } ?>

                </a></td>
               <!--    <td><a data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($getMySalesDa->booking_no); ?>" class=" <?php if ($getMySalesDa->delivery_status == 1 || $getMySalesDa->delivery_status == 2 || $getMySalesDa->delivery_status == 4 || $getMySalesDa->delivery_status == 5 || $getMySalesDa->delivery_status == 6) { ?>
                      btn fs-14 btn-warning btn-upload-1
                    <?php } else{ ?> btn btn-upload btn-uploaded fs-14 Upload <?php } ?>  order_details" style="color:#fff;">
                    <?php if ($getMySalesDa->delivery_status == 1 || $getMySalesDa->delivery_status == 2 || $getMySalesDa->delivery_status == 4 || $getMySalesDa->delivery_status == 5 || $getMySalesDa->delivery_status == 6) { ?>
                      Uploaded
                    <?php } else{ ?> Upload <?php } ?>

                </a></td> -->
                  <td>
                    <h5 class="fs-15"><?php echo $getMySalesDa->match_name; ?> - <?php echo date('l', strtotime($getMySalesDa->match_date));?></h5>
                    <p class="fs-15" ><span><?php echo $getMySalesDa->stadium_country_name . ', ' . $getMySalesDa->stadium_city_name; ?></span></p>
                    <h5 class="fs-15"><!-- Sun 10 Jul 2022  -->
                      <?php echo date('D d M Y', strtotime($getMySalesDa->match_date));?> <?php echo $getMySalesDa->match_time; ?></h5></td>
                      <td>
                    <p>Status</p> <h5 class="fs-15">
                      <?php //echo "<pre>";print_r($getMySalesDa);?>
                        <?php if ($getMySalesDa->booking_status == '' || $getMySalesDa->booking_status == 7) { ?>Booking Not Initiated<?php } ?>
                            <?php if ($getMySalesDa->booking_status == 0) { ?>Failed<?php } ?>
                            <?php if ($getMySalesDa->booking_status == 1) { ?>Confirmed<?php } ?>
                            <?php if ($getMySalesDa->booking_status == 2) { ?>Pending<?php } ?>
                            <?php if ($getMySalesDa->booking_status == 3) { ?>Cancelled<?php } ?>
                            <?php if ($getMySalesDa->booking_status == 4) { ?>Shipped<?php } ?>
                            <?php if ($getMySalesDa->booking_status == 5) { ?>Delivered<?php } ?>
                            <?php if ($getMySalesDa->booking_status == 6) { ?>
                              <i data-feather="download"></i> Downloaded<?php } ?>

                     </h5>
                  </td>
                <td>
                  <h5 class="fs-15"><?php echo $getMySalesDa->seat_category;?> <span>
                    X <?php echo $getMySalesDa->quantity;?>
                      <!-- <i class="fa-solid fa-circle-info info-icon"></i> --></span> </h5>
                  <?php foreach($seller_notes as $seller_note){?>
                  <p><span><?php echo $seller_note->ticket_name;?></span></p>
                  <?php $i++;}?>
                </td>
                 <!--  <td>
                    <h5 class="fs-15">Seller  Notes <span>
                      <i class="fa-solid fa-circle-info info-icon"></i></span> </h5>
                  <?php foreach($seller_notes as $seller_note){?>
                  <p><span><?php echo $seller_note->ticket_name;?></span></p>
                  <?php $i++;}?>
                </td> -->
                  <td>
                    <p>Tickets Via</p>
                    <p> <?php
                                                            if ($getMySalesDa->ticket_type == 1) {
                                                                echo 'Season cards';
                                                            } else
                                                    if ($getMySalesDa->ticket_type == 2) {
                                                                echo "E-Tickets";
                                                            } else
                                                    if ($getMySalesDa->ticket_type == 3) {
                                                                echo "Paper";
                                                            } else  if ($getMySalesDa->ticket_type == 4) {
                                                                echo "Mobile";
                                                            } ?></p>
                                                              <?php if($getMySalesDa->ticket_block != ""){ ?>
                                                            <p>Block:<?php echo $getMySalesDa->ticket_block;?></p>
                                                          <?php } ?>
                                                          <?php if($getMySalesDa->ticket_block != ""){ ?>
                                                            <p>Row:<?php echo $getMySalesDa->row;?></p>
                                                            <?php } ?>
                  </td>
                  <td>
                    <p>Event in:</p> <h5 class="fs-15"><?php echo $event_left;?></h5>
                  </td>
                  <td>
                    <p>#<?php echo $getMySalesDa->booking_no;?></p><p><?php echo date('D d M Y h:i', strtotime($getMySalesDa->created_at));?></p>
                  </td>
                  <td>
                    <h5 class="fs-15"> <?php  if ($this->session->userdata('role') == 1) { ?>
                                                <?php if (strtoupper($getMySalesDa->currency_type) == "GBP") { ?>
                                                            £
                                                            <?php } ?>
                                                <?php if (strtoupper($getMySalesDa->currency_type) == "EUR") { ?>
                                                            €
                                                <?php } 
                                                    if (strtoupper($getMySalesDa->currency_type) != "GBP" && strtoupper($getMySalesDa->currency_type) != "EUR"){
                                                 echo strtoupper($getMySalesDa->currency_type); 
                                                }
                                                ?>
                                                <?= number_format($getMySalesDa->ticket_amount,2) ?> 
                                            <?php } ?>
                                              <?php  if ($this->session->userdata('role') != 1) { ?>
                                                <?php if (strtoupper($getMySalesDa->currency_type) == "GBP") { ?>
                                                          £
                                                            <?php } ?>
                                                <?php if (strtoupper($getMySalesDa->currency_type) == "EUR") { ?>
                                                            €
                                                <?php } 
                                                    if (strtoupper($getMySalesDa->currency_type) != "GBP" && strtoupper($getMySalesDa->currency_type) != "EUR"){
                                                 echo strtoupper($getMySalesDa->currency_type); 
                                                }
                                                ?>
                                                <?= number_format($getMySalesDa->total_amount,2) ?> 
                                            <?php } ?></h5><!-- <?php echo strtoupper($getMySalesDa->currency_type);?> -->
                                          </td>
                                           <td><a href="javascript:void(0);" data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($getMySalesDa->booking_no); ?>" class="order_details"><img src="<?php echo base_url().THEME_NAME;?>/images/search-plus.png" alt="Search"  width="32px"></a></td>
                 <!--  <td><a href="<?php echo base_url(); ?>game/orders/details/<?php echo md5($getMySalesDa->booking_no); ?>"><img src="<?php echo base_url().THEME_NAME;?>/images/search-plus.png" alt="Search"  width="32px"></a></td> -->
                </tr>
                  <?php
                  }
                  }
                  else{ ?>
                  <tr><td colspan="9"><h5 class="dash-welcome-head fs-24">No Orders.</h5></td></tr>
                  <?php }
                  ?>
              
              </tbody>

            </table>
        </section>
      <?php } ?>
    </div>


    <!-- Modal -->
     <div class="my_modal">
        <div class="modal fade bd-example-modal-lg" id="myLargeModalLabel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="order_details">
                  <div class="row">
                <div class="team_name">
                <h3 style="text-align: center;"><i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Please Wait ...</h3>
              </div>
            </div>
               <!--  -->
            </div>
          </div>
        </div>
      </div>
   <?php $this->load->view(THEME_NAME.'/common/footer');?>
     <script type="text/javascript">
    

   
        // $("#content_e3").mCustomScrollbar({
        //   scrollButtons:{
        //     enable:true
        //   }
        // });
   $(document).on("click",".order_details",function() {
   $('#myLargeModalLabel').modal();
    var action = $(this).attr('data-url');
    

     $.ajax({
      type: "POST",
      dataType: "json",
       url: action,
      data: {"filter":''},
      success: function(list) {
        $('#order_details').html(list.orders);
    }
    });




});

</script>