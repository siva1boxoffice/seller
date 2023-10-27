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
                    $event_left =  'Overdue';
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
             
                 <td><a data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($getMySalesDa->booking_no); ?>" class=" <?php if ($getMySalesDa->delivery_status == 1 || $getMySalesDa->delivery_status == 2 || $getMySalesDa->delivery_status == 4 || $getMySalesDa->delivery_status == 5 || $getMySalesDa->delivery_status == 6) { ?>
                      btn fs-14 btn-warning btn-upload-1
                    <?php } else if ($getMySalesDa->delivery_status == 3) {?> btn fs-14 btn-red btn-upload-1 <?php } else{ ?> btn btn-upload btn-uploaded fs-14 Upload <?php } ?>  order_details" style="color:#fff;">
                    <?php if ($getMySalesDa->delivery_status == 1 || $getMySalesDa->delivery_status == 2 || $getMySalesDa->delivery_status == 4 || $getMySalesDa->delivery_status == 5 || $getMySalesDa->delivery_status == 6) { ?>
                      Uploaded
                    <?php }  else if ($getMySalesDa->delivery_status == 3) { ?>
                      Reupload
                    <?php } else{ ?> Upload <?php echo $getMySalesDa->ticket_status; } ?>

                </a></td>
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
                     <!--  <i class="fa-solid fa-circle-info info-icon"></i> --></span> </h5>
                  <?php foreach($seller_notes as $seller_note){?>
                  <p><span><?php echo $seller_note->ticket_name;?></span></p>
                  <?php $i++;}?>
                </td>
                
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
                     <p>Delivery Deadline:</p> <h5 <?php if($daysleft <= 2 && $daysleft >= 0){?> style="color:red;" <?php } ?> class="fs-15">
                      
                      <?php echo $event_left;?></h5>
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
                                                <?php } ?>
                                                <?php if (strtoupper($getMySalesDa->currency_type) == "USD") { ?>
                                                            $
                                                  <?php } ?>
                                                  <?php   if (strtoupper($getMySalesDa->currency_type) != "GBP" && strtoupper($getMySalesDa->currency_type) != "EUR" && strtoupper($getMySalesDa->currency_type) != "USD"){
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
                                                        <?php } ?>
                                               <?php if (strtoupper($getMySalesDa->currency_type) == "USD") { ?>
                                                            $
                                                  <?php } ?>
                                                  <?php   if (strtoupper($getMySalesDa->currency_type) != "GBP" && strtoupper($getMySalesDa->currency_type) != "EUR" && strtoupper($getMySalesDa->currency_type) != "USD"){
                                                 echo strtoupper($getMySalesDa->currency_type); 
                                                }
                                                ?>
                                                <?= number_format($getMySalesDa->total_amount,2) ?> 
                                            <?php } ?></h5><!-- <?php echo strtoupper($getMySalesDa->currency_type);?> -->
                                          </td>
                  <td><a href="javascript:void(0);" data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($getMySalesDa->booking_no); ?>" class="order_details"><img src="<?php echo base_url().THEME_NAME;?>/images/search-plus.png" alt="Search"  width="32px"></a></td>
                </tr>
                  <?php
                  }
                  }
                  else if(($_POST['page'] == 1 || $_POST['page'] == "" )  && empty($getMySalesData)){ ?>
                  <tr><td colspan="9"><h5 class="dash-welcome-head fs-18">No orders found</h5></td></tr>
                  <?php }
                  ?>